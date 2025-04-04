<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('folders', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_public')->default(false);
            $table->boolean('create_for_everyone')->default(false);
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('template_folder_id')->nullable()->constrained('folders')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('folder_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('folder_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('folder_user');
        Schema::dropIfExists('folders');
    }
}; 