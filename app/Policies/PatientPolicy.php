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
        return in_array($user->role, [UserRole::ADMIN, UserRole::RECEPTIONIST]);
    }

    public function update(User $user, Patient $patient): bool
    {
        return in_array($user->role, [UserRole::ADMIN, UserRole::RECEPTIONIST]);
    }

    public function delete(User $user, Patient $patient): bool
    {
        return $user->role === UserRole::ADMIN;
    }
}
