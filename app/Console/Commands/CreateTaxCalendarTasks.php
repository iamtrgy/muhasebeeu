<?php

namespace App\Console\Commands;

use App\Models\TaxCalendar;
use App\Models\Company;
use App\Models\User;
use Illuminate\Console\Command;
use Carbon\Carbon;

class CreateTaxCalendarTasks extends Command
{
    protected $signature = 'tax-calendar:create-tasks';
    protected $description = 'Create tax calendar tasks automatically based on configured schedules';

    public function handle()
    {
        $this->info('Starting to create tax calendar tasks...');

        // Get all active tax calendars that are configured for auto-creation
        $taxCalendars = TaxCalendar::active()
            ->where('auto_create_tasks', true)
            ->get();

        $this->info("Found " . $taxCalendars->count() . " active tax calendars");

        foreach ($taxCalendars as $calendar) {
            $this->info("Processing {$calendar->name}...");

            // Get all companies that need this tax calendar (based on country)
            $companies = Company::where('country_code', $calendar->country_code)
                ->get();

            $this->info("Found " . $companies->count() . " companies for country {$calendar->country_code}");

            foreach ($companies as $company) {
                // Find the assigned accountant for this company
                $accountant = User::whereHas('assignedCompanies', function ($query) use ($company) {
                    $query->where('companies.id', $company->id);
                })->where('is_accountant', true)
                    ->first();

                if (!$accountant) {
                    $this->warn("No accountant assigned for company {$company->name}. Skipping...");
                    continue;
                }

                // Calculate next deadline based on frequency
                $nextDeadline = $calendar->next_deadline;
                
                if (!$nextDeadline) {
                    $this->warn("Could not determine next deadline for {$calendar->name}. Skipping...");
                    continue;
                }

                // Check if task already exists for this deadline
                $taskExists = $calendar->tasks()
                    ->where('company_id', $company->id)
                    ->where('due_date', $nextDeadline)
                    ->exists();

                if ($taskExists) {
                    $this->info("Task already exists for {$company->name} on {$nextDeadline->format('Y-m-d')}. Skipping...");
                    continue;
                }

                // Create the task
                $task = $calendar->createTaskForCompany(
                    $company->id,
                    $accountant->id,
                    $nextDeadline
                );

                if ($task) {
                    $this->info("Created task for {$company->name} due on {$nextDeadline->format('Y-m-d')}");
                } else {
                    $this->error("Failed to create task for {$company->name}");
                }
            }
        }

        $this->info('Finished creating tax calendar tasks.');
    }
} 