<?php

namespace App\Console\Commands;

use App\Models\Folder;
use App\Services\BunnyAdapter;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Config;

class CreateBunnyFolderPlaceholders extends Command
{
    protected $signature = 'bunny:create-folder-placeholders';
    protected $description = 'Create placeholder files in Bunny storage for all existing folders';

    public function handle()
    {
        $folders = Folder::all();
        $bar = $this->output->createProgressBar(count($folders));
        $this->info("Creating placeholder files for {$folders->count()} folders...");

        $created = 0;
        $failed = 0;

        // Get configuration and create adapter directly
        $config = config('filesystems.disks.bunny');
        $adapter = new BunnyAdapter(
            $config['storage_zone_name'],
            $config['api_key'],
            $config['region'] ?? 'de',
            $config['hostname'] ?? null
        );

        foreach ($folders as $folder) {
            try {
                $folderPath = $this->getFolderPath($folder);
                $adapter->write($folderPath . '/.keep', 'Placeholder file to ensure folder exists in storage', new Config());
                $created++;
            } catch (\Exception $e) {
                $this->error("Failed to create placeholder for folder ID {$folder->id}: " . $e->getMessage());
                $failed++;
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Done!");
        $this->info("Created placeholders: {$created}");
        $this->info("Failed: {$failed}");

        return Command::SUCCESS;
    }

    /**
     * Get the full path for a folder, including parent folders
     */
    private function getFolderPath(Folder $folder): string
    {
        $path = 'folders/' . $folder->id;
        
        // Add parent folder paths if necessary
        if ($folder->parent_id) {
            $parentPath = $this->getParentPath($folder->parent);
            if (!empty($parentPath)) {
                $path = $parentPath . '/' . $path;
            }
        }
        
        return $path;
    }
    
    /**
     * Get the path for parent folders
     */
    private function getParentPath(?Folder $folder): string
    {
        if (!$folder) {
            return '';
        }
        
        $path = 'folders/' . $folder->id;
        
        if ($folder->parent_id) {
            $parentPath = $this->getParentPath($folder->parent);
            if (!empty($parentPath)) {
                $path = $parentPath . '/' . $path;
            }
        }
        
        return $path;
    }
} 