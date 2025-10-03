<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Patient;
use App\Models\User;

class PatientPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, [UserRole::ADMIN, UserRole::RECEPTIONIST, UserRole::DENTIST]);
    }

    public function view(User $user, Patient $patient): bool
    {
        if (in_array($user->role, [UserRole::ADMIN, UserRole::RECEPTIONIST])) {
            return true;
        }

        if ($user->role === UserRole::DENTIST) {
            return true;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, [UserRole::ADMIN, UserRole::RECEPTIONIST, UserRole::DENTIST]);
    }

    public function update(User $user, Patient $patient): bool
    {
        return in_array($user->role, [UserRole::ADMIN, UserRole::RECEPTIONIST, UserRole::DENTIST]);
    }

    public function delete(User $user, Patient $patient): bool
    {
        return $user->role === UserRole::ADMIN;
    }

    public function export(User $user, Patient $patient): bool
    {
        // Doctors and receptionists can export patient data for KVKK compliance
        return in_array($user->role, [UserRole::ADMIN, UserRole::RECEPTIONIST, UserRole::DENTIST]);
    }

    public function softDelete(User $user, Patient $patient): bool
    {
        // Doctors and receptionists can perform soft delete for KVKK compliance
        return in_array($user->role, [UserRole::ADMIN, UserRole::RECEPTIONIST, UserRole::DENTIST]);
    }

    public function hardDelete(User $user, Patient $patient): bool
    {
        // Only admin can perform hard delete
        return $user->role === UserRole::ADMIN;
    }

    public function manageConsents(User $user, Patient $patient): bool
    {
        // Doctors and receptionists can manage KVKK consents
        return in_array($user->role, [UserRole::ADMIN, UserRole::RECEPTIONIST, UserRole::DENTIST]);
    }

    public function restore(User $user, Patient $patient): bool
    {
        // Only admin can restore soft-deleted patients
        return $user->role === UserRole::ADMIN;
    }

    public function viewReports(User $user): bool
    {
        // Doctors and receptionists can view KVKK reports
        return in_array($user->role, [UserRole::ADMIN, UserRole::RECEPTIONIST, UserRole::DENTIST]);
    }
}
