<?php

namespace App\Policies;

use App\Models\User;
use App\Enums\UserRole;
use App\Models\PatientTreatment;
use Illuminate\Auth\Access\Response;

class PatientTreatmentPolicy
{
    /**
     * Kullanıcı yeni bir tedavi oluşturabilir mi?
     */
    public function create(User $user): bool
    {
        return in_array($user->role, [UserRole::ADMIN, UserRole::RECEPTIONIST, UserRole::DENTIST]);
    }

    /**
     * Kullanıcı belirli bir tedaviyi güncelleyebilir mi?
     */
    public function update(User $user, PatientTreatment $patientTreatment): bool
    {
        // Yalnızca işlemi yapan doktor ve adminler düzenleyebilir.
        return $user->role === UserRole::ADMIN || ($user->role === UserRole::DENTIST && $patientTreatment->dentist_id === $user->id);
    }

    /**
     * Kullanıcı belirli bir tedaviyi silebilir mi?
     */
    public function delete(User $user, PatientTreatment $patientTreatment): bool
    {
        // Yalnızca adminler silebilir.
        return $user->role === UserRole::ADMIN;
    }
}