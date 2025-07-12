<?php

namespace App\Services;

use App\Models\User;
use App\Models\File;
use App\Models\Folder;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class UserDataService
{
    const CACHE_DURATION = 30; // Minutes
    
    /**
     * Get user with related data
     * 
     * @param User $user
     * @return User
     */
    public function getUserWithDetails(User $user)
    {
        $userCacheKey = "user_data_{$user->id}";
        
        return Cache::remember($userCacheKey, self::CACHE_DURATION * 60, function() use ($user) {
            return User::with([
                'companies.country',
                'folders' => function($query) {
                    $query->withCount('files');
                }
            ])
            ->withCount('folders')
            ->findOrFail($user->id);
        });
    }
    
    /**
     * Get total storage usage for a user
     * 
     * @param User $user
     * @return int
     */
    public function getStorageUsage(User $user)
    {
        $storageCacheKey = "user_storage_{$user->id}";
        
        return Cache::remember($storageCacheKey, self::CACHE_DURATION * 60, function() use ($user) {
            return DB::table('files')
                ->where('uploaded_by', $user->id)
                ->sum('size');
        });
    }
    
    /**
     * Get total files count for a user
     * 
     * @param User $user
     * @return int
     */
    public function getFilesCount(User $user)
    {
        $fileCountCacheKey = "user_file_count_{$user->id}";
        
        return Cache::remember($fileCountCacheKey, self::CACHE_DURATION * 60, function() use ($user) {
            return DB::table('files')
                ->where('uploaded_by', $user->id)
                ->count();
        });
    }
    
    /**
     * Get recent files for a user
     * 
     * @param User $user
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRecentFiles(User $user, $limit = 20)
    {
        $recentFilesCacheKey = "user_recent_files_{$user->id}";
        
        return Cache::remember($recentFilesCacheKey, self::CACHE_DURATION * 60, function() use ($user, $limit) {
            return File::where('uploaded_by', $user->id)
                ->latest()
                ->take($limit)
                ->get();
        });
    }
    
    /**
     * Get activity logs for a user
     * 
     * @param User $user
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getActivityLogs(User $user, $limit = 50)
    {
        $activityCacheKey = "user_activity_{$user->id}";
        
        return Cache::remember($activityCacheKey, self::CACHE_DURATION * 60, function() use ($user, $limit) {
            return ActivityLog::where('user_id', $user->id)
                ->latest()
                ->take($limit)
                ->get();
        });
    }
    
    /**
     * Get main folders for a user
     * 
     * @param User $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getMainFolders(User $user)
    {
        $mainFoldersCacheKey = "main_folders_{$user->id}";
        
        return Cache::remember($mainFoldersCacheKey, self::CACHE_DURATION * 60, function() use ($user) {
            return Folder::where('created_by', $user->id)
                ->whereNull('parent_id')
                ->withCount('files')
                ->get();
        });
    }
    
    /**
     * Get folder and its files
     * 
     * @param int $folderId
     * @return array
     */
    public function getFolderWithFiles($folderId)
    {
        $folderDataCacheKey = "folder_data_{$folderId}";
        $folderFilesCacheKey = "folder_files_{$folderId}";
        
        $folder = Cache::remember($folderDataCacheKey, self::CACHE_DURATION * 60, function() use ($folderId) {
            return Folder::findOrFail($folderId);
        });
        
        $files = Cache::remember($folderFilesCacheKey, self::CACHE_DURATION * 60, function() use ($folderId) {
            return File::where('folder_id', $folderId)
                ->latest()
                ->get();
        });
        
        return [
            'folder' => $folder,
            'files' => $files
        ];
    }
    
    /**
     * Clear all cache entries related to a specific user
     *
     * @param User $user
     * @return void
     */
    public function clearUserCaches(User $user)
    {
        // Clear user-related cache keys
        Cache::forget("user_data_{$user->id}");
        Cache::forget("user_storage_{$user->id}");
        Cache::forget("user_file_count_{$user->id}");
        Cache::forget("user_recent_files_{$user->id}");
        Cache::forget("user_activity_{$user->id}");
        Cache::forget("main_folders_{$user->id}");
        
        // Also clear folder-related caches
        $userFolders = Folder::where('created_by', $user->id)->pluck('id');
        foreach ($userFolders as $folderId) {
            Cache::forget("folder_data_{$folderId}");
            Cache::forget("subfolders_{$folderId}");
            Cache::forget("folder_files_{$folderId}");
        }
    }
}
