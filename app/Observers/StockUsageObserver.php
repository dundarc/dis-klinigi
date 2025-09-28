<?php

namespace App\Observers;

use App\Models\Stock\StockUsage;
use App\Models\Stock\StockUsageItem;
use App\Services\Stock\StockMovementService;
use Illuminate\Support\Facades\Log;

class StockUsageObserver
{
    public function __construct(private readonly StockMovementService $movementService)
    {
    }

    /**
     * Handle the StockUsage "created" event.
     */
    public function created(StockUsage $usage): void
    {
        $this->processUsageItems($usage, 'created');
    }

    /**
     * Handle the StockUsage "updated" event.
     */
    public function updated(StockUsage $usage): void
    {
        // Check if usage items have been modified
        if ($usage->wasChanged(['notes'])) {
            Log::info('Stock usage updated', [
                'usage_id' => $usage->id,
                'changes' => $usage->getChanges()
            ]);
        }
    }

    /**
     * Handle the StockUsage "deleting" event.
     */
    public function deleting(StockUsage $usage): void
    {
        // Reverse stock movements when usage is deleted
        $this->reverseUsageStockMovements($usage);
    }

    /**
     * Process stock movements for usage items
     */
    private function processUsageItems(StockUsage $usage, string $event): void
    {
        try {
            foreach ($usage->items as $item) {
                if ($item->stockItem) {
                    // Check if we can process the outgoing movement
                    if (!$this->movementService->canProcessOutgoing($item->stockItem, (float) $item->quantity)) {
                        Log::warning('Insufficient stock for usage', [
                            'usage_id' => $usage->id,
                            'item_id' => $item->stockItem->id,
                            'item_name' => $item->stockItem->name,
                            'required_quantity' => $item->quantity,
                            'available_quantity' => $item->stockItem->quantity,
                            'allow_negative' => $item->stockItem->allow_negative
                        ]);

                        // If item doesn't allow negative stock, skip this movement
                        if (!$item->stockItem->allow_negative) {
                            continue;
                        }
                    }

                    $this->movementService->recordOutgoing(
                        $item->stockItem,
                        (float) $item->quantity,
                        [
                            'reference_type' => StockUsage::class,
                            'reference_id' => $usage->id,
                            'note' => $this->generateUsageNote($usage, $item),
                            'created_by' => $usage->user_id ?? auth()->id(),
                            'movement_date' => $usage->created_at ?? now(),
                        ]
                    );

                    Log::info('Stock movement recorded for usage', [
                        'usage_id' => $usage->id,
                        'item_id' => $item->stockItem->id,
                        'quantity' => $item->quantity,
                        'event' => $event
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::error('Failed to process stock movements for usage', [
                'usage_id' => $usage->id,
                'error' => $e->getMessage(),
                'event' => $event
            ]);
        }
    }

    /**
     * Reverse stock movements when usage is deleted
     */
    private function reverseUsageStockMovements(StockUsage $usage): void
    {
        try {
            foreach ($usage->items as $item) {
                if ($item->stockItem) {
                    $this->movementService->recordIncoming(
                        $item->stockItem,
                        (float) $item->quantity,
                        [
                            'reference_type' => StockUsage::class,
                            'reference_id' => $usage->id,
                            'note' => "Kullanım silme işlemi: " . $this->generateUsageNote($usage, $item),
                            'created_by' => auth()->id(),
                            'movement_date' => now(),
                        ]
                    );

                    Log::info('Stock movement reversed for deleted usage', [
                        'usage_id' => $usage->id,
                        'item_id' => $item->stockItem->id,
                        'quantity' => $item->quantity
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::error('Failed to reverse stock movements for deleted usage', [
                'usage_id' => $usage->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Generate descriptive note for usage movement
     */
    private function generateUsageNote(StockUsage $usage, StockUsageItem $item): string
    {
        $note = "Kullanım: ";
        
        if ($usage->encounter) {
            $note .= "Ziyaret #{$usage->encounter->id}";
            if ($usage->encounter->patient) {
                $note .= " - {$usage->encounter->patient->first_name} {$usage->encounter->patient->last_name}";
            }
        } elseif ($usage->patientTreatment) {
            $note .= "Tedavi #{$usage->patientTreatment->id}";
            if ($usage->patientTreatment->patient) {
                $note .= " - {$usage->patientTreatment->patient->first_name} {$usage->patientTreatment->patient->last_name}";
            }
        } else {
            $note .= "Genel Kullanım #{$usage->id}";
        }

        if ($item->stockItem) {
            $note .= " - {$item->stockItem->name}";
        }

        return $note;
    }
}