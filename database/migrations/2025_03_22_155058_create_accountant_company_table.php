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
        Schema::create('accountant_company', function (Blueprint $table) {
            $table->id();
            $table->foreignId('accountant_id')->constrained('users');
            $table->foreignId('company_id')->constrained('companies');
            $table->timestamps();

            $table->unique(['accountant_id', 'company_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accountant_company');
    }
};
