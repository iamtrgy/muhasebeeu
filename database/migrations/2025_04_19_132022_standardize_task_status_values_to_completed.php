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
        // Convert existing statuses to new status values
        DB::table('tax_calendar_tasks')
            ->where('status', 'under_review')
            ->update(['status' => 'pending']);

        DB::table('tax_calendar_tasks')
            ->where('status', 'approved')
            ->update(['status' => 'in_progress']);

        DB::table('tax_calendar_tasks')
            ->where('status', 'completed')
            ->update(['status' => 'in_progress']);

        // Add check constraint to ensure only valid statuses
        if (DB::connection()->getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE tax_calendar_tasks ADD CONSTRAINT check_valid_status CHECK (status IN ('pending', 'changes_requested', 'rejected', 'in_progress', 'completed'))");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove the check constraint if not SQLite
        if (DB::connection()->getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE tax_calendar_tasks DROP CONSTRAINT IF EXISTS check_valid_status");
        }

        // Convert statuses back to original values
        DB::table('tax_calendar_tasks')
            ->where('status', 'pending')
            ->update(['status' => 'under_review']);

        DB::table('tax_calendar_tasks')
            ->whereIn('status', ['in_progress', 'completed'])
            ->update(['status' => 'approved']);
    }
};
