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
        Schema::table('companies', function (Blueprint $table) {
            $table->string('bank_name', 100)->nullable()->after('phone');
            $table->string('bank_account', 50)->nullable()->after('bank_name');
            $table->string('bank_iban', 50)->nullable()->after('bank_account');
            $table->string('bank_swift', 20)->nullable()->after('bank_iban');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn(['bank_name', 'bank_account', 'bank_iban', 'bank_swift']);
        });
    }
};
