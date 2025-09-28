<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\EncounterStatus;
use App\Models\Encounter;
use App\Models\User;

class EncounterPolicy
{
    public function update(User $user, Encounter $encounter): bool
    {
        // Allow if user is admin or the dentist assigned to this encounter
        return $user->role === \App\Enums\UserRole::ADMIN || $encounter->dentist_id === $user->id;
    }

    public function complete(User $user, Encounter $encounter): bool
    {
        // Check if user can update
        if (!$this->update($user, $encounter)) {
            return false;
        }

        // Encounter must have at least one treatment plan item to be marked as DONE
        return $encounter->treatmentPlanItems()->exists();
    }
}
