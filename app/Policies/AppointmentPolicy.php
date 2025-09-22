<?php

namespace App\Policies;

use App\Models\Appointment;
use App\Models\User;
use App\Enums\UserRole;

class AppointmentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Tüm roller randevu listesini görebilir (Controller'da kapsam daraltılacak).
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Appointment $appointment): bool
    {
        // Admin, Resepsiyonist ve Asistan her şeyi görebilir.
        if (in_array($user->role, [UserRole::ADMIN, UserRole::RECEPTIONIST, UserRole::ASSISTANT])) {
            return true;
        }
        // Hekim sadece kendi randevusunu görebilir.
        return $user->id === $appointment->dentist_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Tüm roller randevu oluşturabilir.
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Appointment $appointment): bool
    {
        // Admin, Resepsiyonist ve Asistan her şeyi güncelleyebilir.
        if (in_array($user->role, [UserRole::ADMIN, UserRole::RECEPTIONIST, UserRole::ASSISTANT])) {
            return true;
        }
        // Hekim sadece kendi randevusunu güncelleyebilir.
        return $user->id === $appointment->dentist_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Appointment $appointment): bool
    {
        // Sadece Admin ve randevunun sahibi olan Hekim silebilir.
        if ($user->role === UserRole::DENTIST) {
            return $user->id === $appointment->dentist_id;
        }
        return $user->role === UserRole::ADMIN;
    }
}