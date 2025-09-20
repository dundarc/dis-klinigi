<?php
namespace App\Policies;

use App\Models\Prescription;
use App\Models\User;
use App\Enums\UserRole;

class PrescriptionPolicy
{
    public function view(User $user, Prescription $prescription): bool
    {
        if ($user->role === UserRole::RECEPTIONIST) return true;
        return $user->id === $prescription->dentist_id;
    }

    public function create(User $user): bool
    {
        return $user->role === UserRole::DENTIST;
    }

    public function update(User $user, Prescription $prescription): bool
    {
        return $user->id === $prescription->dentist_id;
    }
    
    public function delete(User $user, Prescription $prescription): bool
    {
        return $user->id === $prescription->dentist_id;
    }
}