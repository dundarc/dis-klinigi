<?php

namespace App\Policies;

use App\Enums\AppointmentStatus;
use App\Enums\UserRole;
use App\Models\Appointment;
use App\Models\User;

class AppointmentPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, [UserRole::ADMIN, UserRole::RECEPTIONIST, UserRole::DENTIST]);
    }

    public function view(User $user, Appointment $appointment): bool
    {
        if (in_array($user->role, [UserRole::ADMIN, UserRole::RECEPTIONIST])) {
            return true;
        }

        if ($user->role === UserRole::DENTIST) {
            return $user->id === $appointment->dentist_id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, [UserRole::ADMIN, UserRole::RECEPTIONIST, UserRole::DENTIST]);
    }

    public function update(User $user, Appointment $appointment): bool
    {
        if ($user->role === UserRole::ADMIN) {
            return true;
        }

        if ($appointment->status === AppointmentStatus::COMPLETED) {
            return false;
        }

        if ($user->role === UserRole::RECEPTIONIST) {
            return true;
        }

        if ($user->role === UserRole::DENTIST) {
            return $user->id === $appointment->dentist_id;
        }

        return false;
    }

    public function delete(User $user, Appointment $appointment): bool
    {
        if ($user->role === UserRole::ADMIN) {
            return true;
        }

        if ($appointment->status === AppointmentStatus::COMPLETED) {
            return false;
        }

        if ($user->role === UserRole::RECEPTIONIST) {
            return true;
        }

        if ($user->role === UserRole::DENTIST) {
            return $user->id === $appointment->dentist_id;
        }

        return false;
    }
}
