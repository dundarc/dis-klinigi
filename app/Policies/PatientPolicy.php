<?php

namespace App\Policies;

use App\Models\Patient;
use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Auth\Access\Response;

class PatientPolicy
{
    /**
     * Kullanıcı tüm hastaları listeleyebilir mi?
     */
    public function viewAny(User $user): bool
    {
        return $user->role === UserRole::ADMIN || $user->role === UserRole::RECEPTIONIST;
    }

    /**
     * Kullanıcı belirli bir hastayı görüntüleyebilir mi?
     */
    public function view(User $user, Patient $patient): bool
    {
        // Admin ve Resepsiyonist tüm hastaları görebilir.
        if ($user->role === UserRole::ADMIN || $user->role === UserRole::RECEPTIONIST) {
            return true;
        }

        // Hekim, sadece en az bir randevusu olan hastayı görebilir.
        if ($user->role === UserRole::DENTIST) {
            return $patient->appointments()->where('dentist_id', $user->id)->exists();
        }

        return false;
    }

    /**
     * Kullanıcı hasta oluşturabilir mi?
     */
    public function create(User $user): bool
{
    return in_array($user->role, [UserRole::ADMIN, UserRole::RECEPTIONIST, UserRole::DENTIST]);
}

    /**
     * Kullanıcı hasta bilgilerini güncelleyebilir mi?
     */
    public function update(User $user, Patient $patient): bool
    {
        // Güncelleme iznini, görme izniyle aynı tutabiliriz.
        return $this->view($user, $patient);
    }

    /**
     * Kullanıcı hasta kaydını silebilir mi?
     */
    public function delete(User $user, Patient $patient): bool
    {
        // Sadece Admin'ler silebilir.
        return $user->role === UserRole::ADMIN;
    }
}