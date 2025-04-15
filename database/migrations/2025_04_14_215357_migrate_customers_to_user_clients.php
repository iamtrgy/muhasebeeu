<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use App\Models\UserClient;
use Illuminate\Support\Facades\Artisan;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First run the previous migration to ensure the user_clients table exists
        Artisan::call('migrate', ['--path' => 'database/migrations/2025_04_14_215305_create_user_clients_table.php']);

        // Transfer data from customers to user_clients
        $customers = Customer::all();
        foreach ($customers as $customer) {
            UserClient::create([
                'id' => $customer->id, // Keep same IDs for easier reference
                'user_id' => $customer->user_id,
                'name' => $customer->name,
                'email' => $customer->email,
                'phone' => $customer->phone,
                'vat_number' => $customer->vat_number,
                'company_reg_number' => $customer->company_reg_number,
                'country' => $customer->country,
                'address' => $customer->address,
                'created_at' => $customer->created_at,
                'updated_at' => $customer->updated_at,
            ]);
        }

        // Update the foreign key in invoices table if it exists
        if (Schema::hasTable('invoices') && Schema::hasColumn('invoices', 'client_id')) {
            // We don't need to change the IDs in invoices table since we kept the same IDs
            // But we should update references from Customer to UserClient in the codebase
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Clear data from user_clients
        UserClient::truncate();
    }
};
