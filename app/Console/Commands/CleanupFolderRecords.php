<?php

namespace App\Console\Commands;

use App\Models\Folder;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanupFolderRecords extends Command
{
    protected $signature = 'folders:cleanup';
    protected $description = 'Clean up folder records and their relationships';

    public function handle()
    {
        $this->info('Starting folder records cleanup...');

        DB::beginTransaction();
        try {
            // 1. Remove folder_user entries for non-existent users or folders
            $this->info('Cleaning up folder_user relationships...');
            $deletedRelations = DB::table('folder_user')
                ->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('users')
                        ->whereRaw('users.id = folder_user.user_id');
                })
                ->orWhereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('folders')
                        ->whereRaw('folders.id = folder_user.folder_id');
                })
                ->delete();
            $this->info("Removed {$deletedRelations} invalid folder-user relationships.");

            // 2. Clean up all soft-deleted folders
            $this->info('Cleaning up all soft-deleted folders...');
            $deletedFolders = Folder::onlyTrashed()->get();

            foreach ($deletedFolders as $folder) {
                // Remove all folder-user relationships
                $folder->users()->detach();
                
                // Force delete the folder
                $folder->forceDelete();
            }
            $this->info("Permanently deleted {$deletedFolders->count()} soft-deleted folders.");

            // 3. Clean up orphaned folders (no users attached and not created by anyone)
            $this->info('Cleaning up orphaned folders...');
            $orphanedFolders = Folder::whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('folder_user')
                    ->whereRaw('folders.id = folder_user.folder_id');
            })
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('users')
                    ->whereRaw('users.id = folders.created_by');
            })
            ->get();

            foreach ($orphanedFolders as $folder) {
                $folder->delete();
            }
            $this->info("Removed {$orphanedFolders->count()} orphaned folders.");

            // 4. Update folder statistics
            $this->info('Updating folder statistics...');
            $activeFolders = Folder::whereNull('deleted_at')->count();
            $softDeletedFolders = Folder::onlyTrashed()->count();
            $totalFolders = $activeFolders + $softDeletedFolders;

            DB::commit();

            $this->info("\nCleanup completed successfully!");
            $this->table(
                ['Status', 'Count'],
                [
                    ['Active Folders', $activeFolders],
                    ['Soft-Deleted Folders', $softDeletedFolders],
                    ['Total Folders', $totalFolders],
                ]
            );

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Error during cleanup: " . $e->getMessage());
            return 1;
        }

        return 0;
    }
} 