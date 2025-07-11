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
            $table->json('ai_analysis')->nullable()->after('notes');
            $table->timestamp('ai_analyzed_at')->nullable()->after('ai_analysis');
            $table->unsignedBigInteger('ai_suggested_folder_id')->nullable()->after('ai_analyzed_at');
            $table->boolean('ai_suggestion_accepted')->default(false)->after('ai_suggested_folder_id');
            
            $table->index('ai_analyzed_at');
            $table->foreign('ai_suggested_folder_id')->references('id')->on('folders')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('files', function (Blueprint $table) {
            $table->dropForeign(['ai_suggested_folder_id']);
            $table->dropColumn([
                'ai_analysis',
                'ai_analyzed_at',
                'ai_suggested_folder_id',
                'ai_suggestion_accepted'
            ]);
        });
    }
};