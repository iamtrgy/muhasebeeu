<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if customers table exists
        if (!Schema::hasTable('customers')) {
            return;
        }

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
        ");

        // Update the foreign key in invoices table if it exists
        if (Schema::hasTable('invoices') && Schema::hasColumn('invoices', 'client_id')) {
            // No need to update IDs since we kept the same IDs
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Clear data from user_clients
        DB::statement('TRUNCATE TABLE user_clients');
    }
};
