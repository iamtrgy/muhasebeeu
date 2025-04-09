<?php

namespace App\Console\Commands;

use App\Models\Folder;
use Illuminate\Console\Command;

class CheckFolderStructure extends Command
{
    protected $signature = 'folders:check';
    protected $description = 'Check folder structure and paths';

    public function handle()
    {
        $folders = Folder::with(['parent', 'company'])
            ->select(['id', 'name', 'parent_id', 'company_id', 'path', 'active'])
            ->get();

        $this->info("Found " . $folders->count() . " folders");
        
        foreach ($folders as $folder) {
            $this->line(sprintf(
                "ID: %d | Name: %s | Parent: %s | Company: %s | Path: %s | Active: %s",
                $folder->id,
                $folder->name,
                $folder->parent ? $folder->parent->name : 'None',
                $folder->company ? $folder->company->name : 'None',
                $folder->path ?? 'None',
                $folder->active ? 'Yes' : 'No'
            ));
        }
    }
}
