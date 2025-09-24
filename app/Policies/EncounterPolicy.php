<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Encounter;
use App\Models\User;

class EncounterPolicy
{
    public function createEmergency(User $user): bool
    {
        return in_array($user->role, [UserRole::ADMIN, UserRole::RECEPTIONIST, UserRole::DENTIST]);
    }

    public function update(User $user, Encounter $encounter): bool
    {
        if ($user->role === UserRole::ADMIN) {
            return true;
        }

        if ($user->role === UserRole::RECEPTIONIST) {
            return true;
        }

        if ($user->role === UserRole::DENTIST) {
            return $encounter->dentist_id === $user->id;
        }

        return false;
    }
}
