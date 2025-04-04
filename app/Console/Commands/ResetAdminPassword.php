<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ResetAdminPassword extends Command
{
    protected $signature = 'admin:reset-password {email?} {password?}';
    protected $description = 'Reset the password for an admin user';

    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->argument('password') ?? 'password123'; // Default password if none provided

        if (!$email) {
            $email = $this->ask('What is the admin email address?');
        }

        // Find the user
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("User with email {$email} not found.");
            
            // List all users to help identify the correct email
            $this->info('Available users:');
            $users = User::all(['id', 'name', 'email', 'is_admin']);
            $this->table(['ID', 'Name', 'Email', 'Is Admin'], $users->toArray());
            
            return 1;
        }

        // Update the user's password
        $user->password = Hash::make($password);
        $user->save();

        // Also ensure the user is marked as an admin
        if (!$user->is_admin) {
            $user->is_admin = true;
            $user->save();
            $this->info("User {$email} has been marked as an admin.");
        }

        $this->info("Password for {$email} has been reset to: {$password}");
        
        return 0;
    }
} 