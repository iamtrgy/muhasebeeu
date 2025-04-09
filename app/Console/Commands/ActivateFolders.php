<?php

namespace App\Console\Commands;

use App\Models\Folder;
use Illuminate\Console\Command;

class ActivateFolders extends Command
{
    protected $signature = 'folders:activate';
    protected $description = 'Activate all folders';

    public function handle()
    {
        $count = Folder::where('active', false)->update(['active' => true]);
        $this->info("Activated {$count} folders");
    }
}
