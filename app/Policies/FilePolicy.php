<?php
namespace App\Policies;

use App\Models\File;
use App\Models\User;
use App\Enums\UserRole;

class FilePolicy
{
    public function view(User $user, File $file): bool
    {
        if ($user->role === UserRole::RECEPTIONIST) return true;
        // Hekim, hastanın herhangi bir dosyasını görebilir (şimdilik)
        // Daha detaylı olarak "sadece kendi hastasının" diye kısıtlanabilir.
        return $user->role === UserRole::DENTIST;
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