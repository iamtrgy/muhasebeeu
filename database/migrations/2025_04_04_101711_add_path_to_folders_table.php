<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('folders', function (Blueprint $table) {
            $table->string('path')->nullable()->after('parent_id');
            $table->index('path');
        });

        // Generate paths for existing folders
        DB::table('folders')->orderBy('id')->chunk(100, function ($folders) {
            foreach ($folders as $folder) {
                $path = $folder->parent_id
                    ? DB::table('folders')->where('id', $folder->parent_id)->value('path') . '/' . $folder->id
                    : (string) $folder->id;
                
                DB::table('folders')
                    ->where('id', $folder->id)
                    ->update(['path' => $path]);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('folders', function (Blueprint $table) {
            $table->dropIndex(['path']);
            $table->dropColumn('path');
        });
    }
};
