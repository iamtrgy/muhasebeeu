<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TaxCalendar;
use App\Models\TaxCalendarTask;
use Illuminate\Support\Facades\DB;

class MergeDuplicateTaxCalendars extends Command
{
    protected $signature = 'tax-calendar:merge-duplicates {--dry-run : Show what would be done without making changes}';
    protected $description = 'Merge duplicate tax calendars and their tasks';

    public function handle()
    {
        $this->info('Starting to merge duplicate tax calendars...');
        $dryRun = $this->option('dry-run');
        
        if ($dryRun) {
            $this->warn('DRY RUN MODE: No changes will be made to the database.');
        }

        // Get all tax calendars grouped by name
        $taxCalendars = TaxCalendar::all()->groupBy('name');
        
        // Find duplicate tax calendars (same name)
        $duplicateCalendars = $taxCalendars->filter(function($group) {
            return $group->count() > 1;
        });
        
        if ($duplicateCalendars->isEmpty()) {
            $this->info('No duplicate tax calendars found.');
            return;
        }
        
        $this->info('Found ' . $duplicateCalendars->count() . ' groups of duplicate tax calendars.');
        
        foreach ($duplicateCalendars as $name => $calendars) {
            $this->info("\nProcessing duplicate tax calendars for: $name");
            
            // Sort by ID to keep the lowest ID as the primary
            $sortedCalendars = $calendars->sortBy('id');
            $primaryCalendar = $sortedCalendars->first();
            $duplicates = $sortedCalendars->slice(1);
            
            $this->info("  Primary calendar: ID {$primaryCalendar->id}, Form Code: {$primaryCalendar->form_code}");
            
            foreach ($duplicates as $duplicate) {
                $this->info("  Duplicate calendar: ID {$duplicate->id}, Form Code: {$duplicate->form_code}");
                
                // Get tasks for this duplicate calendar
                $tasks = TaxCalendarTask::where('tax_calendar_id', $duplicate->id)->get();
                $this->info("    Found {$tasks->count()} tasks to migrate.");
                
                foreach ($tasks as $task) {
                    // Check if a task with the same company and due date already exists for the primary calendar
                    $existingTask = TaxCalendarTask::where('tax_calendar_id', $primaryCalendar->id)
                        ->where('company_id', $task->company_id)
                        ->where('due_date', $task->due_date)
                        ->first();
                    
                    if ($existingTask) {
                        $this->warn("    Task ID {$task->id} has a duplicate (ID {$existingTask->id}) in the primary calendar - will be removed.");
                        if (!$dryRun) {
                            $task->delete();
                        }
                    } else {
                        $this->info("    Migrating task ID {$task->id} to primary calendar.");
                        if (!$dryRun) {
                            $task->tax_calendar_id = $primaryCalendar->id;
                            $task->save();
                        }
                    }
                }
                
                // Delete the duplicate tax calendar
                if (!$dryRun) {
                    $this->info("    Deleting duplicate tax calendar ID {$duplicate->id}.");
                    $duplicate->delete();
                } else {
                    $this->info("    Would delete duplicate tax calendar ID {$duplicate->id}.");
                }
            }
        }
        
        if ($dryRun) {
            $this->warn('DRY RUN COMPLETE: No changes were made. Run without --dry-run to apply changes.');
        } else {
            $this->info("\nMerge complete. Duplicate tax calendars have been merged.");
        }
    }
}
