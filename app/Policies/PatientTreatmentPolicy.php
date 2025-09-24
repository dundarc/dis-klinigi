<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\PatientTreatment;
use App\Models\User;

class PatientTreatmentPolicy
{
    public function create(User $user): bool
    {
        return in_array($user->role, [UserRole::ADMIN, UserRole::RECEPTIONIST, UserRole::DENTIST]);
    }

    public function update(User $user, PatientTreatment $patientTreatment): bool
    {
        if ($user->role === UserRole::ADMIN) {
            return true;
        }

        if ($user->role === UserRole::DENTIST) {
            return $patientTreatment->dentist_id === $user->id;
        }

        return false;
    }

    public function delete(User $user, PatientTreatment $patientTreatment): bool
    {
        if ($user->role === UserRole::ADMIN) {
            return true;
        }

        if ($user->role === UserRole::DENTIST) {
            return $patientTreatment->dentist_id === $user->id;
        }

        return false;
    }
}
