<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Folder;

class FixFolderStructure extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'folders:fix';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix folder structure inconsistencies';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting folder structure fix...');

        DB::transaction(function () {
            // Get all folders including soft deleted ones
            $folders = Folder::withTrashed()->get();

            foreach ($folders as $folder) {
                // Check if parent exists and is not deleted when the child is not deleted
                if ($folder->parent_id && !$folder->trashed()) {
                    $parent = Folder::withTrashed()->find($folder->parent_id);
                    if ($parent && $parent->trashed()) {
                        $this->warn("Found active folder '{$folder->name}' with deleted parent. Soft deleting...");
                        $folder->delete();
                    }
                }

                // Clean up user relationships for deleted folders
                if ($folder->trashed()) {
                    $folder->users()->detach();
                }
            }
        });

        $this->info('Folder structure fix completed.');

        // Show current folder statistics
        $this->table(
            ['Status', 'Count'],
            [
                ['Total Folders', Folder::withTrashed()->count()],
                ['Active Folders', Folder::count()],
                ['Deleted Folders', Folder::onlyTrashed()->count()],
            ]
        );
    }
}
