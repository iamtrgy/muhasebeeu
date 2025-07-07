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
            // Check if column doesn't exist before adding
            if (!Schema::hasColumn('files', 'notes')) {
                // Add notes column - nullable to protect existing data
                $table->text('notes')->nullable()->after('mime_type');
            }
            
            // Don't add index on TEXT column - MySQL doesn't support it without key length
            // If you need to search notes, consider using fulltext index instead:
            // $table->fullText('notes');
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
            // Drop the notes column
            $table->dropColumn('notes');
        });
    }
};