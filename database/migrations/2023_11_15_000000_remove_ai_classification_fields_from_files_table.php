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
        Schema::table('files', function (Blueprint $table) {
            $table->dropForeign(['suggested_folder_id']);
            $table->dropColumn(['suggested_folder_id', 'classification_reviewed']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('files', function (Blueprint $table) {
            $table->unsignedBigInteger('suggested_folder_id')->nullable();
            $table->boolean('classification_reviewed')->default(false);
            
            $table->foreign('suggested_folder_id')
                  ->references('id')
                  ->on('folders')
                  ->onDelete('set null');
        });
    }
};
