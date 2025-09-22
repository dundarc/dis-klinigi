<?php

namespace App\Policies;

use App\Models\Encounter;
use App\Models\User;
use App\Enums\UserRole;

class EncounterPolicy
{
    /**
     * Determine whether the user can create emergency encounters.
     */
    public function createEmergency(User $user): bool
    {
        // Acil kaydı sadece Admin ve Resepsiyonist oluşturabilir.
        return in_array($user->role, [UserRole::ADMIN, UserRole::RECEPTIONIST]);
    }

    /**
     * Determine whether the user can update the model.
     * Bu metodu bir sonraki adımda dolduracağız.
     */
    public function update(User $user, Encounter $encounter): bool
    {
        return in_array($user->role, [UserRole::ADMIN, UserRole::RECEPTIONIST]);
    }
}