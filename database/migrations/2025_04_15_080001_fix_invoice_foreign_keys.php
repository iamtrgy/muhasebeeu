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
        // First, drop the existing foreign key if it exists
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['client_id']);
        });

        // Then add the correct foreign key
        Schema::table('invoices', function (Blueprint $table) {
            $table->foreign('client_id')
                  ->references('id')
                  ->on('user_clients')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['client_id']);
        });
    }
}; 