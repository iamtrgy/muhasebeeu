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
        Schema::table('folders', function (Blueprint $table) {
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('set null')->after('created_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('folders', function (Blueprint $table) {
            // Drop foreign key constraint first
            $table->dropForeign(['company_id']);
            // Then drop the column
            $table->dropColumn('company_id');
        });
    }
};
