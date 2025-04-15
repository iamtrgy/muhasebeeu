<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            // Drop the existing foreign key constraint
            $table->dropForeign(['client_id']);
            
            // Add the new foreign key constraint referencing user_clients
            $table->foreign('client_id')->references('id')->on('user_clients')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            // Drop the new foreign key constraint
            $table->dropForeign(['client_id']);
            
            // Restore the original foreign key constraint
            $table->foreign('client_id')->references('id')->on('customers')->nullOnDelete();
        });
    }
}; 