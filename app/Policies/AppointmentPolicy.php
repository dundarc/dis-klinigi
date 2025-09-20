<?php

namespace App\Policies;

use App\Models\Appointment;
use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Auth\Access\Response;

class AppointmentPolicy
{
    /**
     * Kullanıcı tüm randevuları listeleyebilir mi?
     */
    public function viewAny(User $user): bool
    {
        // Admin ve Resepsiyonist tüm randevuları görebilir.
        return $user->role === UserRole::ADMIN || $user->role === UserRole::RECEPTIONIST;
    }

    /**
     * Kullanıcı belirli bir randevuyu görüntüleyebilir mi?
     */
    public function view(User $user, Appointment $appointment): bool
    {
        // Admin ve Resepsiyonist tüm randevuları görebilir.
        if ($user->role === UserRole::ADMIN || $user->role === UserRole::RECEPTIONIST) {
            return true;
        }

        // Hekim ise sadece kendi randevusunu görebilir.
        if ($user->role === UserRole::DENTIST) {
            return $user->id === $appointment->dentist_id;
        }
        
        return false;
    }

    /**
     * Kullanıcı randevu oluşturabilir mi?
     */
    public function create(User $user): bool
    {
        // Sadece Admin ve Resepsiyonist randevu oluşturabilir.
        return $user->role === UserRole::ADMIN || $user->role === UserRole::RECEPTIONIST;
    }

    /**
     * Kullanıcı belirli bir randevuyu güncelleyebilir mi?
     */
    public function update(User $user, Appointment $appointment): bool
    {
        // Admin ve Resepsiyonist tüm randevuları güncelleyebilir.
        if ($user->role === UserRole::ADMIN || $user->role === UserRole::RECEPTIONIST) {
            return true;
        }

        // Hekim ise sadece kendi randevusunu güncelleyebilir.
        if ($user->role === UserRole::DENTIST) {
            return $user->id === $appointment->dentist_id;
        }
        
        return false;
    }

    /**
     * Kullanıcı belirli bir randevuyu silebilir mi?
     */
    public function delete(User $user, Appointment $appointment): bool
    {
        // Sadece Admin'ler silebilir.
        return $user->role === UserRole::ADMIN;
    }

    public function refer(User $user, Appointment $appointment): bool
{
    // Sadece hekimler, kendi randevularını sevk edebilir.
    return $user->role === UserRole::DENTIST && $user->id === $appointment->dentist_id;
}

/**
 * Kullanıcı kendisine sevk edilen bir randevuyu kabul edebilir mi?
 */
public function acceptReferral(User $user, Appointment $appointment): bool
{
    // Sadece hekimler, kendilerine sevk edilmiş ve durumu 'beklemede' olan bir randevuyu kabul edebilir.
    return $user->role === UserRole::DENTIST && 
           $user->id === $appointment->dentist_id && // Randevu artık bu hekime atanmış olmalı
           $appointment->referral_status === 'pending'; // Örnek durum
}
}

