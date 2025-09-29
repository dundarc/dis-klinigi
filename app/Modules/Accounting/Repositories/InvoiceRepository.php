<?php

namespace App\Modules\Accounting\Repositories;

use App\Models\Invoice;
use App\Enums\InvoiceStatus;
use Illuminate\Database\Eloquent\Collection;
use Carbon\Carbon;

class InvoiceRepository
{
    public function find(int $id): ?Invoice
    {
        return Invoice::find($id);
    }

    public function findWithRelations(int $id): ?Invoice
    {
        return Invoice::with(['patient', 'items', 'payments'])->find($id);
    }

    public function create(array $data): Invoice
    {
        return Invoice::create($data);
    }

    public function update(Invoice $invoice, array $data): Invoice
    {
        $invoice->update($data);
        return $invoice->fresh();
    }

    public function delete(Invoice $invoice): bool
    {
        return $invoice->delete();
    }

    public function getAllPaginated(int $perPage = 20)
    {
        return Invoice::with(['patient', 'items', 'payments'])->latest()->paginate($perPage);
    }

    public function getByPatient(int $patientId, int $perPage = 20)
    {
        return Invoice::with(['patient', 'items', 'payments'])
            ->where('patient_id', $patientId)
            ->latest()
            ->paginate($perPage);
    }

    public function getByStatus(InvoiceStatus $status, int $perPage = 20)
    {
        return Invoice::with(['patient', 'items', 'payments'])
            ->where('status', $status)
            ->latest()
            ->paginate($perPage);
    }

    public function getOverdueInvoices(): Collection
    {
        return Invoice::where('status', InvoiceStatus::OVERDUE)
            ->orWhere(function ($query) {
                $query->where('due_date', '<', now()->startOfDay())
                      ->where('status', InvoiceStatus::POSTPONED);
            })
            ->with(['patient', 'items', 'payments'])
            ->get();
    }

    public function getRevenueBetweenDates(Carbon $startDate, Carbon $endDate): float
    {
        return Invoice::where('status', InvoiceStatus::PAID)
            ->whereBetween('paid_at', [$startDate, $endDate])
            ->sum('grand_total');
    }

    public function getTrashedInvoices(int $perPage = 20)
    {
        return Invoice::onlyTrashed()
            ->with(['patient', 'items', 'payments'])
            ->latest('deleted_at')
            ->paginate($perPage);
    }

    public function restore(int $id): bool
    {
        $invoice = Invoice::withTrashed()->find($id);
        return $invoice ? $invoice->restore() : false;
    }

    public function forceDelete(int $id): bool
    {
        $invoice = Invoice::withTrashed()->find($id);
        return $invoice ? $invoice->forceDelete() : false;
    }

    public function search(array $filters, int $perPage = 20)
    {
        $query = Invoice::with(['patient', 'items', 'payments'])->latest('issue_date');

        if (!empty($filters['patient_id'])) {
            $query->where('patient_id', $filters['patient_id']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('issue_date', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('issue_date', '<=', $filters['date_to']);
        }

        return $query->paginate($perPage)->withQueryString();
    }
}