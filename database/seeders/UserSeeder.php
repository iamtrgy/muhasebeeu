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
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'is_admin' => true,
            'is_accountant' => false,
            'onboarding_completed' => true,
            'email_verified_at' => now(),
        ]);

        // Create test user
        User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'is_admin' => false,
            'is_accountant' => false,
            'onboarding_completed' => true,
            'email_verified_at' => now(),
        ]);

        // Create accountant user
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