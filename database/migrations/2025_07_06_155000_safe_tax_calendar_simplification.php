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
        // First check which columns exist to avoid errors
        $columns = Schema::getColumnListing('tax_calendar_tasks');
        
        // Build the update query dynamically based on existing columns
        $updateParts = [];
        $whereParts = ["status != 'pending'"];
        
        if (in_array('submitted_at', $columns)) {
            $updateParts[] = "CASE WHEN submitted_at IS NOT NULL THEN CONCAT('Submitted: ', submitted_at, '\n') ELSE '' END";
            $whereParts[] = "submitted_at IS NOT NULL";
        }
        
        // Check for both possible column names for reviewed date
        if (in_array('reviewed_at', $columns)) {
            $updateParts[] = "CASE WHEN reviewed_at IS NOT NULL THEN CONCAT('Reviewed: ', reviewed_at, '\n') ELSE '' END";
            $whereParts[] = "reviewed_at IS NOT NULL";
        } elseif (in_array('review_feedback_date', $columns)) {
            $updateParts[] = "CASE WHEN review_feedback_date IS NOT NULL THEN CONCAT('Reviewed: ', review_feedback_date, '\n') ELSE '' END";
            $whereParts[] = "review_feedback_date IS NOT NULL";
        }
        
        if (in_array('review_feedback', $columns)) {
            $updateParts[] = "CASE WHEN review_feedback IS NOT NULL THEN CONCAT('Feedback: ', review_feedback, '\n') ELSE '' END";
            $whereParts[] = "review_feedback IS NOT NULL";
        }
        
        if (in_array('completed_at', $columns)) {
            $updateParts[] = "CASE WHEN completed_at IS NOT NULL THEN CONCAT('Completed: ', completed_at, '\n') ELSE '' END";
            $whereParts[] = "completed_at IS NOT NULL";
        }
        
        // Only run if there are columns to migrate
        if (!empty($updateParts)) {
            $updateString = implode(",\n                ", $updateParts);
            $whereString = implode(" OR ", $whereParts);
            
            DB::statement("
                UPDATE tax_calendar_tasks 
                SET user_notes = CONCAT(
                    COALESCE(user_notes, ''),
                    CASE WHEN user_notes IS NOT NULL THEN '\n\n' ELSE '' END,
                    '--- Migrated Data ---\n',
                    'Original Status: ', COALESCE(status, 'pending'), '\n',
                    {$updateString}
                )
                WHERE {$whereString}
            ");
        }
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