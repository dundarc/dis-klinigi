<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\EncounterStatus;
use App\Models\Encounter;
use App\Models\User;

class EncounterPolicy
{
    public function view(User $user, Encounter $encounter): bool
    {
        // Same rules as update for consistency
        return $this->update($user, $encounter);
    }

    public function update(User $user, Encounter $encounter): bool
    {
        // Allow if user is admin
        if ($user->role === \App\Enums\UserRole::ADMIN) {
            return true;
        }

        // Allow if user is the dentist assigned to this encounter
        if ($encounter->dentist_id === $user->id) {
            return true;
        }

        // Allow if encounter has no dentist assigned (emergency cases)
        if ($encounter->dentist_id === null) {
            return true;
        }

        // Allow if user is a dentist and encounter is in waiting/in_service status
        if ($user->role === \App\Enums\UserRole::DENTIST &&
            in_array($encounter->status->value, [\App\Enums\EncounterStatus::WAITING->value, \App\Enums\EncounterStatus::IN_SERVICE->value])) {
            return true;
        }

        return false;
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
