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
        // First, identify and remove duplicate tasks
        $tasks = DB::table('tax_calendar_tasks')
            ->select('tax_calendar_id', 'company_id', 'due_date', DB::raw('MIN(id) as keep_id'))
            ->groupBy('tax_calendar_id', 'company_id', 'due_date')
            ->get();
            
        foreach ($tasks as $task) {
            // Delete all duplicates except the one we want to keep
            DB::table('tax_calendar_tasks')
                ->where('tax_calendar_id', $task->tax_calendar_id)
                ->where('company_id', $task->company_id)
                ->where('due_date', $task->due_date)
                ->where('id', '!=', $task->keep_id)
                ->delete();
        }
        
        // Add the unique constraint if it doesn't exist
        // For SQLite, we need to check if the index exists first
        $indexExists = DB::select(
            "SELECT name FROM sqlite_master 
             WHERE type = 'index' 
             AND name = 'tax_calendar_tasks_unique_constraint'"
        );
        
        if (empty($indexExists)) {
            Schema::table('tax_calendar_tasks', function (Blueprint $table) {
                $table->unique(['tax_calendar_id', 'company_id', 'due_date'], 'tax_calendar_tasks_unique_constraint');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tax_calendar_tasks', function (Blueprint $table) {
            //
        });
    }
};
