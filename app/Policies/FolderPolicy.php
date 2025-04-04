<?php

namespace App\Policies;

use App\Models\Folder;
use App\Models\User;

class FolderPolicy
{
    public function view(User $user, Folder $folder)
    {
        return $folder->is_public || $folder->users->contains($user->id) || $user->is_admin || $user->is_accountant;
    }

    public function upload(User $user, Folder $folder)
    {
        return $folder->is_public || $folder->users->contains($user->id) || $user->is_admin;
    }

    public function create(User $user): bool
    {
        return $user->is_admin;
    }

    public function update(User $user, Folder $folder): bool
    {
        return $user->is_admin;
    }

    public function delete(User $user, Folder $folder): bool
    {
        return $user->is_admin;
    }
} 