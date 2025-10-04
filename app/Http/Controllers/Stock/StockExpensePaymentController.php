<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Models\Stock\StockExpense;
use App\Models\StockExpensePayment;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class StockExpensePaymentController extends Controller
{
    public function store(Request $request, StockExpense $expense): RedirectResponse
    {
        $this->authorize('accessStockManagement');

        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01', 'max:' . $expense->remaining_amount],
            'payment_date' => ['required', 'date'],
            'payment_method' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
        ]);

        $expense->payments()->create($validated);

        // Update payment status
        $expense->updatePaymentStatus();

        return redirect()->back()->with('success', 'Ödeme başarıyla eklendi.');
    }

    public function destroy(StockExpense $expense, StockExpensePayment $payment): RedirectResponse
    {
        $this->authorize('accessStockManagement');

        // Ensure the payment belongs to the expense
        if ($payment->expense_id !== $expense->id) {
            abort(404);
        }

        $payment->delete();

        // Update payment status
        $expense->updatePaymentStatus();

        return redirect()->back()->with('success', 'Ödeme başarıyla silindi.');
    }
}
