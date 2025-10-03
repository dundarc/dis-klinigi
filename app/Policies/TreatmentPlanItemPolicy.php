<?php

namespace App\Policies;

use App\Models\TreatmentPlanItem;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TreatmentPlanItemPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TreatmentPlanItem $treatmentPlanItem): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TreatmentPlanItem $treatmentPlanItem): bool
    {
        // Admin'ler her zaman izinli
        if ($user->role === \App\Enums\UserRole::ADMIN) {
            return true;
        }

        // Doktorlar treatment plan item'larını güncelleyebilir
        if ($user->role === \App\Enums\UserRole::DENTIST) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TreatmentPlanItem $treatmentPlanItem): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, TreatmentPlanItem $treatmentPlanItem): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, TreatmentPlanItem $treatmentPlanItem): bool
    {
        return false;
    }

    /**
     * Determine whether the user can complete the treatment plan item.
     */
    public function complete(User $user, TreatmentPlanItem $treatmentPlanItem): bool
    {
        return $this->update($user, $treatmentPlanItem);
    }

    /**
     * Determine whether the user can cancel the treatment plan item.
     */
    public function cancel(User $user, TreatmentPlanItem $treatmentPlanItem): bool
    {
        return $this->update($user, $treatmentPlanItem);
    }

    /**
     * Determine whether the user can start the treatment plan item.
     */
    public function start(User $user, TreatmentPlanItem $treatmentPlanItem): bool
    {
        return $this->update($user, $treatmentPlanItem);
    }
}
