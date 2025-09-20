<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\PatientXray;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PatientXrayPolicy
{
    /**
     * Kullanıcı yeni bir röntgen görseli oluşturabilir mi?
     */
    public function create(User $user): bool
    {
        return in_array($user->role, [UserRole::ADMIN, UserRole::RECEPTIONIST, UserRole::DENTIST]);
    }

    /**
     * Kullanıcı belirli bir röntgen görselini güncelleyebilir mi?
     */
    public function update(User $user, PatientXray $patientXray): bool
    {
        // Yalnızca Admin veya görseli yükleyen hekim düzenleyebilir.
        return $user->role === UserRole::ADMIN || ($user->role === UserRole::DENTIST && $patientXray->uploader_id === $user->id);
    }

    /**
     * Kullanıcı belirli bir röntgen görselini silebilir mi?
     */
    public function delete(User $user, PatientXray $patientXray): bool
    {
        // Yalnızca Admin silebilir.
        return $user->role === UserRole::ADMIN;
    }
}