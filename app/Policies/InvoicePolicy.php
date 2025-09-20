<?php
namespace App\Policies;

use App\Models\Invoice;
use App\Models\User;
use App\Enums\UserRole;

class InvoicePolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, [UserRole::RECEPTIONIST, UserRole::ACCOUNTANT, UserRole::DENTIST]);
    }

    public function view(User $user, Invoice $invoice): bool
    {
        if (in_array($user->role, [UserRole::RECEPTIONIST, UserRole::ACCOUNTANT])) {
            return true;
        }
        // Hekim, sadece kendi yaptığı bir işleme ait faturayı görebilir.
        return $invoice->items()->whereHas('patientTreatment', fn($q) => $q->where('dentist_id', $user->id))->exists();
    }

    public function create(User $user): bool
    {
        return in_array($user->role, [UserRole::RECEPTIONIST, UserRole::DENTIST]);
    }

    public function update(User $user, Invoice $invoice): bool
    {
        // Muhasebeci ve Resepsiyonist fatura durumunu güncelleyebilir
        if (in_array($user->role, [UserRole::ACCOUNTANT, UserRole::RECEPTIONIST])) {
            return true;
        }
        // Hekim sadece kendi oluşturduğu faturayı düzenleyebilir
        return $this->view($user, $invoice);
    }

    public function delete(User $user, Invoice $invoice): bool
    {
        // Hekim sadece kendi faturasını silebilir
        return $this->view($user, $invoice);
    }
}