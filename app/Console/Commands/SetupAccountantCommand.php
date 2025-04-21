<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class SetupAccountantCommand extends Command
{
    protected $signature = 'setup:accountant';
    protected $description = 'Set up an accountant user';

    public function handle()
    {
        $this->info('Setting up accountant...');

        // Get all users
        $users = User::all();
        
        if ($users->isEmpty()) {
            $this->error('No users found in the system.');
            return 1;
        }

        // List all users
        $this->info('Available users:');
        $users->each(function ($user) {
            $this->line("[{$user->id}] {$user->name} ({$user->email})");
        });

        // Ask which user to make accountant
        $userId = $this->ask('Which user ID should be made an accountant?');
        
        $user = User::find($userId);
        if (!$user) {
            $this->error('User not found.');
            return 1;
        }

        $user->update(['is_accountant' => true]);
        
        $this->info("Successfully made {$user->name} an accountant!");
        
        return 0;
    }
} 