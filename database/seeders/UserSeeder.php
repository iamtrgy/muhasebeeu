<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user if not exists
        if (!User::where('email', 'admin@example.com')->exists()) {
            User::create([
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => Hash::make('password123'),
                'is_admin' => true,
                'is_accountant' => false,
                'onboarding_completed' => true,
                'email_verified_at' => now(),
            ]);
        }

        // Create test user if not exists
        if (!User::where('email', 'test@example.com')->exists()) {
            User::create([
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => Hash::make('password123'),
                'is_admin' => false,
                'is_accountant' => false,
                'onboarding_completed' => true,
                'email_verified_at' => now(),
            ]);
        }

        // Create accountant user if not exists
        if (!User::where('email', 'accountant@example.com')->exists()) {
            User::create([
                'name' => 'Accountant User',
                'email' => 'accountant@example.com',
                'password' => Hash::make('password123'),
                'is_admin' => false,
                'is_accountant' => true,
                'onboarding_completed' => true,
                'email_verified_at' => now(),
            ]);
        }
    }
} 