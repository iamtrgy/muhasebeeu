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
        Schema::create('accountant_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('accountant_id')->constrained('users');
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();

            $table->unique(['accountant_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accountant_user');
    }
};
