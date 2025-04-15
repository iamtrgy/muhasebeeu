<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        try {
            // Check if customers table exists
            if (!Schema::hasTable('customers')) {
                Log::warning('Customers table does not exist, skipping migration');
                return;
            }

            // Log the number of records to be migrated
            $count = DB::table('customers')->count();
            Log::info("Starting migration of {$count} customers to user_clients");

            // Use direct SQL for better performance
            DB::statement("
                INSERT INTO user_clients (
                    id, user_id, name, email, phone, vat_number, 
                    company_reg_number, country, address, created_at, updated_at
                )
                SELECT 
                    id, user_id, name, email, phone, vat_number, 
                    company_reg_number, country, address, created_at, updated_at
                FROM customers
                WHERE NOT EXISTS (
                    SELECT 1 FROM user_clients WHERE user_clients.id = customers.id
                )
            ");

            // Log completion
            $migratedCount = DB::table('user_clients')->count();
            Log::info("Successfully migrated {$migratedCount} customers to user_clients");

        } catch (\Exception $e) {
            Log::error('Error during customer migration: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Don't truncate, just log
        Log::warning('Migration rollback called for migrate_customers_to_user_clients');
    }
};
