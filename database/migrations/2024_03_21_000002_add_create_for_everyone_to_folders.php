<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('folders', function (Blueprint $table) {
            if (!Schema::hasColumn('folders', 'create_for_everyone')) {
                $table->boolean('create_for_everyone')->default(false)->after('is_public');
            }
            if (!Schema::hasColumn('folders', 'template_folder_id')) {
                $table->foreignId('template_folder_id')->nullable()->after('created_by')
                    ->constrained('folders')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('folders', function (Blueprint $table) {
            if (Schema::hasColumn('folders', 'template_folder_id')) {
                $table->dropForeign(['template_folder_id']);
            }
            if (Schema::hasColumn('folders', 'create_for_everyone') && Schema::hasColumn('folders', 'template_folder_id')) {
                $table->dropColumn(['create_for_everyone', 'template_folder_id']);
            } else {
                if (Schema::hasColumn('folders', 'create_for_everyone')) {
                    $table->dropColumn('create_for_everyone');
                }
                if (Schema::hasColumn('folders', 'template_folder_id')) {
                    $table->dropColumn('template_folder_id');
                }
            }
        });
    }
}; 