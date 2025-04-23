<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TaxCalendarTask;
use App\Models\Company;
use App\Models\TaxCalendar;
use Illuminate\Support\Facades\DB;

class DiagnoseTaxCalendarTasks extends Command
{
    protected $signature = 'tax-calendar:diagnose {company_id? : Optional company ID to filter tasks}';
    protected $description = 'Diagnose issues with tax calendar tasks';

    public function handle()
    {
        $this->info('Starting tax calendar task diagnosis...');

        $companyId = $this->argument('company_id');
        
        // Get all tasks grouped by tax_calendar_id, company_id, and due_date
        $query = DB::table('tax_calendar_tasks')
            ->select(
                'tax_calendar_tasks.id',
                'tax_calendar_tasks.tax_calendar_id',
                'tax_calendar_tasks.company_id',
                'tax_calendar_tasks.due_date',
                'tax_calendar_tasks.status',
                'tax_calendars.name as calendar_name',
                'companies.name as company_name'
            )
            ->join('tax_calendars', 'tax_calendars.id', '=', 'tax_calendar_tasks.tax_calendar_id')
            ->join('companies', 'companies.id', '=', 'tax_calendar_tasks.company_id')
            ->orderBy('tax_calendar_tasks.tax_calendar_id')
            ->orderBy('tax_calendar_tasks.company_id')
            ->orderBy('tax_calendar_tasks.due_date');
            
        if ($companyId) {
            $query->where('tax_calendar_tasks.company_id', $companyId);
        }
        
        $tasks = $query->get();
        
        $this->info("Found {$tasks->count()} total tasks.");
        
        // Group tasks by tax_calendar_id, company_id, and due_date
        $groupedTasks = [];
        foreach ($tasks as $task) {
            $key = "{$task->tax_calendar_id}_{$task->company_id}_{$task->due_date}";
            if (!isset($groupedTasks[$key])) {
                $groupedTasks[$key] = [];
            }
            $groupedTasks[$key][] = $task;
        }
        
        // Check for duplicates
        $duplicateGroups = array_filter($groupedTasks, function($group) {
            return count($group) > 1;
        });
        
        $this->info("Found " . count($duplicateGroups) . " groups with potential duplicates.");
        
        // Display duplicates
        foreach ($duplicateGroups as $key => $group) {
            $this->info("\nDuplicate group for key: $key");
            $this->table(
                ['ID', 'Tax Calendar', 'Company', 'Due Date', 'Status'],
                array_map(function($task) {
                    return [
                        $task->id,
                        "{$task->calendar_name} (ID: {$task->tax_calendar_id})",
                        "{$task->company_name} (ID: {$task->company_id})",
                        $task->due_date,
                        $task->status
                    ];
                }, $group)
            );
        }
        
        // Check for tasks with the same name and due date but different tax_calendar_id
        $this->info("\nChecking for tasks with the same name and due date but different tax_calendar_id...");
        
        $calendarsByName = TaxCalendar::get()->groupBy('name');
        $duplicateCalendars = $calendarsByName->filter(function($group) {
            return $group->count() > 1;
        });
        
        if ($duplicateCalendars->count() > 0) {
            $this->warn("Found tax calendars with duplicate names:");
            foreach ($duplicateCalendars as $name => $calendars) {
                $this->info("  Name: $name");
                foreach ($calendars as $calendar) {
                    $this->info("    ID: {$calendar->id}, Form Code: {$calendar->form_code}");
                }
            }
            
            // Check for tasks that might appear as duplicates in the UI
            $tasksByNameAndDate = [];
            foreach ($tasks as $task) {
                $nameAndDateKey = "{$task->calendar_name}_{$task->due_date}_{$task->company_id}";
                if (!isset($tasksByNameAndDate[$nameAndDateKey])) {
                    $tasksByNameAndDate[$nameAndDateKey] = [];
                }
                $tasksByNameAndDate[$nameAndDateKey][] = $task;
            }
            
            $uiDuplicates = array_filter($tasksByNameAndDate, function($group) {
                return count($group) > 1;
            });
            
            if (count($uiDuplicates) > 0) {
                $this->warn("\nFound tasks that might appear as duplicates in the UI (same name, company, and due date but different tax calendar IDs):");
                foreach ($uiDuplicates as $key => $group) {
                    $this->info("\nPotential UI duplicates for: $key");
                    $this->table(
                        ['ID', 'Tax Calendar', 'Company', 'Due Date', 'Status'],
                        array_map(function($task) {
                            return [
                                $task->id,
                                "{$task->calendar_name} (ID: {$task->tax_calendar_id})",
                                "{$task->company_name} (ID: {$task->company_id})",
                                $task->due_date,
                                $task->status
                            ];
                        }, $group)
                    );
                }
            } else {
                $this->info("No tasks found that would appear as duplicates in the UI.");
            }
        } else {
            $this->info("No tax calendars with duplicate names found.");
        }
        
        $this->info("\nDiagnosis complete.");
    }
}
