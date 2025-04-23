<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\TaxCalendarTask;

class CleanupDuplicateTasks extends Command
{
    protected $signature = 'tax-calendar:cleanup-duplicates';
    protected $description = 'Clean up duplicate tax calendar tasks';

    public function handle()
    {
        $this->info('Starting duplicate task cleanup...');

        // Get all tasks grouped by tax_calendar_id, company_id, and due_date
        $duplicateGroups = DB::table('tax_calendar_tasks')
            ->select('tax_calendar_id', 'company_id', 'due_date', DB::raw('COUNT(*) as count'))
            ->groupBy('tax_calendar_id', 'company_id', 'due_date')
            ->having('count', '>', 1)
            ->get();

        $this->info("Found {$duplicateGroups->count()} groups of duplicate tasks.");

        $totalRemoved = 0;

        foreach ($duplicateGroups as $group) {
            // Get all tasks in this group
            $tasks = TaxCalendarTask::where('tax_calendar_id', $group->tax_calendar_id)
                ->where('company_id', $group->company_id)
                ->where('due_date', $group->due_date)
                ->orderBy('id')
                ->get();

            // Keep the first task (oldest by ID) and remove the rest
            $keepTask = $tasks->shift();
            
            $this->info("Keeping task ID: {$keepTask->id} for tax calendar ID: {$group->tax_calendar_id}, company ID: {$group->company_id}, due date: {$group->due_date}");
            
            foreach ($tasks as $task) {
                $this->info("Removing duplicate task ID: {$task->id}");
                $task->delete();
                $totalRemoved++;
            }
        }

        $this->info("Cleanup complete. Removed {$totalRemoved} duplicate tasks.");

        // Now add a unique constraint if it doesn't exist
        try {
            $this->info("Attempting to add unique constraint...");
            
            // Check if the constraint already exists
            $constraintExists = false;
            
            if (DB::connection()->getDriverName() === 'mysql') {
                $constraintCheck = DB::select("
                    SELECT COUNT(*) as count
                    FROM information_schema.table_constraints 
                    WHERE constraint_name = 'tax_calendar_tasks_unique_constraint'
                    AND table_name = 'tax_calendar_tasks'
                ");
                $constraintExists = $constraintCheck[0]->count > 0;
            } else {
                // For SQLite and other databases
                try {
                    DB::statement('
                        CREATE UNIQUE INDEX tax_calendar_tasks_unique_constraint 
                        ON tax_calendar_tasks (tax_calendar_id, company_id, due_date)
                    ');
                    $this->info("Unique constraint added successfully.");
                    return;
                } catch (\Exception $e) {
                    if (strpos($e->getMessage(), 'already exists') !== false) {
                        $constraintExists = true;
                    } else {
                        throw $e;
                    }
                }
            }
            
            if (!$constraintExists) {
                DB::statement('
                    ALTER TABLE tax_calendar_tasks 
                    ADD CONSTRAINT tax_calendar_tasks_unique_constraint 
                    UNIQUE (tax_calendar_id, company_id, due_date)
                ');
                $this->info("Unique constraint added successfully.");
            } else {
                $this->info("Unique constraint already exists.");
            }
        } catch (\Exception $e) {
            $this->error("Error adding unique constraint: " . $e->getMessage());
        }
    }
}
