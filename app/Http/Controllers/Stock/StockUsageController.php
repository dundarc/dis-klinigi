<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Models\Stock\StockItem;
use App\Models\Stock\StockUsage;
use App\Models\Stock\StockUsageItem;
use App\Services\Stock\StockMovementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class StockUsageController extends Controller
{
    public function __construct(private readonly StockMovementService $movementService)
    {
    }

    public function index(Request $request): View
    {
        $this->authorize('accessStockManagement');

        $query = StockUsage::with(['recordedBy', 'items.stockItem'])
            ->latest('used_at')
            ->latest();

        if ($userId = $request->input('user_id')) {
            $query->where('recorded_by', $userId);
        }

        if ($from = $request->input('date_from')) {
            $query->whereDate('used_at', '>=', $from);
        }

        if ($to = $request->input('date_to')) {
            $query->whereDate('used_at', '<=', $to);
        }

        return view('stock.usage.index', [
            'usages' => $query->paginate(20)->withQueryString(),
            'items' => StockItem::orderBy('name')->get(),
        ]);
    }

    public function create(): View
    {
        $this->authorize('recordStockUsage');

        return view('stock.usage.create', [
            'items' => StockItem::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('recordStockUsage');

        $validated = $request->validate([
            'used_at' => ['nullable', 'date'],
            'encounter_id' => ['nullable', 'exists:encounters,id'],
            'patient_treatment_id' => ['nullable', 'exists:patient_treatments,id'],
            'notes' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.stock_item_id' => ['required', 'exists:stock_items,id'],
            'items.*.quantity' => ['required', 'numeric', 'gt:0'],
            'items.*.notes' => ['nullable', 'string'],
        ]);

        try {
            DB::transaction(function () use ($request, $validated) {
                $usage = StockUsage::create([
                    'recorded_by' => $request->user()->id,
                    'encounter_id' => $validated['encounter_id'] ?? null,
                    'patient_treatment_id' => $validated['patient_treatment_id'] ?? null,
                    'used_at' => $validated['used_at'] ?? now(),
                    'notes' => $validated['notes'] ?? null,
                ]);

                foreach ($validated['items'] as $line) {
                    $item = StockItem::findOrFail($line['stock_item_id']);

                    $usageItem = new StockUsageItem([
                        'quantity' => (float) $line['quantity'],
                        'notes' => $line['notes'] ?? null,
                    ]);

                    $usageItem->stockItem()->associate($item);
                    $usage->items()->save($usageItem);

                    $this->movementService->recordOutgoing($item, (float) $line['quantity'], [
                        'reference_type' => StockUsage::class,
                        'reference_id' => $usage->id,
                        'note' => 'Stok kullanimi',
                        'created_by' => $request->user()->id,
                    ]);
                }
            });
        } catch (\InvalidArgumentException $exception) {
            throw ValidationException::withMessages([
                'items' => [$exception->getMessage()],
            ]);
        }

        return redirect()->route('stock.usage.create')->with('success', 'Kullanim kaydi olusturuldu.');
    }
}
