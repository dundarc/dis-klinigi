<?php

namespace App\Policies;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class KvkkPolicy
{
    /**
     * Determine whether the user can view KVKK index.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('accessAccountingFeatures') || $user->isAccountant();
    }

    /**
     * Determine whether the user can view the patient's KVKK data.
     */
    public function view(User $user, Patient $patient): bool
    {
        return $user->can('accessAccountingFeatures') || $user->isAccountant();
    }

    /**
     * Determine whether the user can export patient data.
     */
    public function export(User $user, Patient $patient): bool
    {
        return $user->can('accessAccountingFeatures') || $user->isAccountant();
    }

    /**
     * Determine whether the user can perform soft delete on patient.
     */
    public function softDelete(User $user, Patient $patient): bool
    {
        // Any authenticated user can request soft delete for their own data
        // Or accountants can do it for any patient
        return $user->can('accessAccountingFeatures') || $user->isAccountant();
    }

    /**
     * Determine whether the user can perform hard delete on patient (admin only).
     */
    public function hardDelete(User $user, Patient $patient): bool
    {
        return $user->can('accessAdminFeatures');
    }

    /**
     * Determine whether the user can restore deleted patient data (admin only).
     */
    public function restore(User $user, Patient $patient): bool
    {
        return $user->can('accessAdminFeatures');
    }

    /**
     * Determine whether the user can view missing consents report.
     */
    public function viewReports(User $user): bool
    {
        return $user->can('accessAccountingFeatures') || $user->isAccountant();
    }

    /**
     * Determine whether the user can manage consents.
     */
    public function manageConsents(User $user, Patient $patient): bool
    {
        return $user->can('accessAccountingFeatures') || $user->isAccountant();
    }
}
