<?php

namespace App\Policies;

use App\Models\File;
use App\Models\User;

class FilePolicy
{
    public function view(User $user, File $file): bool
    {
        // If no folder, check if user uploaded the file
        if (!$file->folder) {
            return $user->id === $file->uploaded_by;
        }
        
        // Use the folder's access check method
        return $file->folder->isAccessibleBy($user);
    }

    public function download(User $user, File $file): bool
    {
        // If no folder, check if user uploaded the file
        if (!$file->folder) {
            return $user->id === $file->uploaded_by;
        }
        
        // Use the folder's access check method
        return $file->folder->isAccessibleBy($user);
    }

    public function delete(User $user, File $file): bool
    {
        return $user->id === $file->uploaded_by;
    }

    public function update(User $user, File $file): bool
    {
        // Allow admins to update any file
        if ($user->is_admin) {
            return true;
        }
        
        // Allow file uploader to update their own files
        if ($user->id === $file->uploaded_by) {
            return true;
        }
        
        // Allow access if user has access to the folder containing the file
        if ($file->folder && $file->folder->users->contains($user->id)) {
            return true;
        }
        
        // Check if user is accountant with access to this file
        return $this->accountantAccess($user, $file);
    }

    public function accountantAccess(User $user, File $file): bool
    {
        // Check if the user is an accountant
        if (!$user->is_accountant) {
            return false;
        }

        // If the file has a folder, check folder owner access
        if ($file->folder) {
            $folderOwner = $file->folder->creator;
            
            // Check if the accountant has access to this user's files
            if ($folderOwner && $user->assignedUsers->contains($folderOwner->id)) {
                return true;
            }
        }
        
        // Check if the file uploader is accessible to the accountant
        if ($file->uploaded_by) {
            $uploader = User::find($file->uploaded_by);
            if ($uploader && $user->assignedUsers->contains($uploader->id)) {
                return true;
            }
        }
        
        // Check if the accountant has access to any company associated with this file
        if ($file->company) {
            return $user->assignedCompanies->contains($file->company->id);
        }
        
        return false;
    }
} 