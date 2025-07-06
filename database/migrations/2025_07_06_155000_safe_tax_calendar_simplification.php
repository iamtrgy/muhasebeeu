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
        Schema::table('tax_calendar_tasks', function (Blueprint $table) {
            // Add new simplified fields only if they don't exist
            if (!Schema::hasColumn('tax_calendar_tasks', 'is_completed')) {
                $table->boolean('is_completed')->default(false)->after('status');
            }
            if (!Schema::hasColumn('tax_calendar_tasks', 'user_notes')) {
                $table->text('user_notes')->nullable()->after('notes');
            }
        });
        
        // SAFELY consolidate data - preserve user progress
        DB::statement("
            UPDATE tax_calendar_tasks 
            SET 
                checklist = CASE 
                    WHEN user_checklist IS NOT NULL THEN user_checklist
                    ELSE checklist
                END,
                is_completed = CASE 
                    WHEN status IN ('completed', 'approved') THEN true
                    ELSE false
                END
        ");
        
        // Create backup of complex data in user_notes field before removing
        DB::statement("
            UPDATE tax_calendar_tasks 
            SET user_notes = CONCAT(
                COALESCE(user_notes, ''),
                CASE WHEN user_notes IS NOT NULL THEN '\n\n' ELSE '' END,
                '--- Migrated Data ---\n',
                'Original Status: ', COALESCE(status, 'pending'), '\n',
                CASE WHEN submitted_at IS NOT NULL THEN CONCAT('Submitted: ', submitted_at, '\n') ELSE '' END,
                CASE WHEN review_feedback_date IS NOT NULL THEN CONCAT('Reviewed: ', review_feedback_date, '\n') ELSE '' END,
                CASE WHEN review_feedback IS NOT NULL THEN CONCAT('Feedback: ', review_feedback, '\n') ELSE '' END,
                CASE WHEN completed_at IS NOT NULL THEN CONCAT('Completed: ', completed_at, '\n') ELSE '' END
            )
            WHERE status != 'pending' OR submitted_at IS NOT NULL OR review_feedback_date IS NOT NULL OR review_feedback IS NOT NULL
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tax_calendar_tasks', function (Blueprint $table) {
            $table->dropColumn(['is_completed', 'user_notes']);
        });
    }
};