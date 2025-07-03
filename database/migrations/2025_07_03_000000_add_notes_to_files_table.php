<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('files', function (Blueprint $table) {
            // Add notes column - nullable to protect existing data
            $table->text('notes')->nullable()->after('mime_type');
            
            // Add index for better performance when searching notes
            $table->index(['notes'], 'files_notes_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('files', function (Blueprint $table) {
            // Drop index first
            $table->dropIndex('files_notes_index');
            
            // Drop the notes column
            $table->dropColumn('notes');
        });
    }
};