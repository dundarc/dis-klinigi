<?php
namespace App\Policies;

use App\Models\Invoice;
use App\Models\User;
use App\Enums\UserRole;

class InvoicePolicy
{
    // "before" metodu kaldırıldı. Yetkiler artık her metodun içinde açıkça kontrol ediliyor.

    public function viewAny(User $user): bool
    {
        if ($user->role === UserRole::ADMIN) {
            return true;
        }
        return in_array($user->role, [UserRole::RECEPTIONIST, UserRole::ACCOUNTANT, UserRole::DENTIST]);
    }

    public function view(User $user, Invoice $invoice): bool
    {
        if ($user->role === UserRole::ADMIN) {
            return true;
        }
        if (in_array($user->role, [UserRole::RECEPTIONIST, UserRole::ACCOUNTANT])) {
            return true;
        }
        // Hekim, sadece kendi yaptığı bir işleme ait faturayı görebilir.
        return $invoice->items()->whereHas('patientTreatment', fn($q) => $q->where('dentist_id', $user->id))->exists();
    }

    public function create(User $user): bool
    {
        if ($user->role === UserRole::ADMIN) {
            return true;
        }
        return in_array($user->role, [UserRole::RECEPTIONIST, UserRole::DENTIST]);
    }

    public function update(User $user, Invoice $invoice): bool
    {
        // NİHAİ DÜZELTME: Admin yetkisi en başa, doğrudan bu metoda eklendi.
        if ($user->role === UserRole::ADMIN) {
            return true;
        }

        // Muhasebeci ve Resepsiyonist fatura durumunu güncelleyebilir
        if (in_array($user->role, [UserRole::ACCOUNTANT, UserRole::RECEPTIONIST])) {
            return true;
        }
        
        // Hekim sadece kendi oluşturduğu faturayı düzenleyebilir
        if ($user->role === UserRole::DENTIST) {
            return $invoice->items()->whereHas('patientTreatment', fn($q) => $q->where('dentist_id', $user->id))->exists();
        }

        return false;
    }

    public function delete(User $user, Invoice $invoice): bool
    {
        // NİHAİ DÜZELTME: Admin yetkisi silme metoduna da eklendi.
        if ($user->role === UserRole::ADMIN) {
            return true;
        }
        
        // Hekim sadece kendi faturasını silebilir
        if ($user->role === UserRole::DENTIST) {
            return $invoice->items()->whereHas('patientTreatment', fn($q) => $q->where('dentist_id', $user->id))->exists();
        }

        return false;
    }
}

