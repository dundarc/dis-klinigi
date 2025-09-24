<?php
namespace App\Policies;

use App\Models\File;
use App\Models\User;
use App\Enums\UserRole;

class FilePolicy
{
    public function view(User $user, File $file): bool
    {
        if (in_array($user->role, [UserRole::ADMIN, UserRole::RECEPTIONIST], true)) {
            return true;
        }

        if ($user->role === UserRole::DENTIST) {
            return true;
        }

        return $user->id === $file->uploaded_by;
    }

    public function create(User $user): bool
    {
        return $user->role === UserRole::DENTIST;
    }

    public function delete(User $user, File $file): bool
    {
        // Dosyayı sadece yükleyen kişi (hekim) silebilir
        return $user->id === $file->uploaded_by;
    }
}