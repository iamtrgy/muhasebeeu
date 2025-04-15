<?php

namespace App\Services;

use App\Models\Folder;
use App\Models\User;
use App\Models\Company;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class FolderStructureService
{
    /**
     * Create company folders structure for a user
     * 
     * @param User $user
     * @param Company $company
     * @return void
     */
    public function createCompanyFolders(User $user, Company $company)
    {
        try {
            // Create root company folder (no upload permission)
            $rootFolder = Folder::create([
                'name' => $company->name,
                'created_by' => $user->id,
                'parent_id' => null,
                'company_id' => $company->id,
                'is_public' => false,
                'allow_uploads' => false, // Root folder should not allow uploads
                'path' => '/' . $company->name,
            ]);

            // Associate the folder with the user
            $rootFolder->users()->attach($user->id);

            // Get foundation date or company creation date
            if ($company->foundation_date) {
                $startDate = Carbon::parse($company->foundation_date);
            } else {
                Log::warning("Company {$company->id} has no foundation date, using created_at date");
                $startDate = $company->created_at;
            }

            if (!$startDate) {
                Log::error("Company {$company->id} has no valid start date");
                throw new \Exception("No valid start date found for company");
            }

            $startYear = $startDate->year;
            $startMonth = $startDate->month;
            $currentDate = Carbon::now();
            
            // Create folders for each year
            for ($year = $startYear; $year <= $currentDate->year; $year++) {
                $yearFolder = Folder::create([
                    'name' => (string) $year,
                    'created_by' => $user->id,
                    'parent_id' => $rootFolder->id,
                    'company_id' => $company->id,
                    'is_public' => false,
                    'allow_uploads' => false,
                    'path' => $rootFolder->path . '/' . $year,
                ]);
                
                // Associate the folder with the user
                $yearFolder->users()->attach($user->id);
                
                // For first year, start from foundation month
                // For current year, create all months including future months
                $monthStart = ($year == $startYear) ? $startMonth : 1;
                $monthEnd = 12;
                
                // Create month folders
                $this->createMonthFolders($user, $yearFolder, $company->id, $monthStart, $monthEnd);
            }
        } catch (\Exception $e) {
            Log::error("Error creating folder structure: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Create month folders for a year
     * 
     * @param User $user
     * @param Folder $yearFolder
     * @param int $companyId
     * @param int $startMonth
     * @param int $endMonth
     * @return void
     */
    private function createMonthFolders(User $user, Folder $yearFolder, int $companyId, int $startMonth = 1, int $endMonth = 12)
    {
        $months = [
            1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 
            5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 
            9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
        ];
        
        // Only create folders for months from startMonth to endMonth
        for ($i = $startMonth; $i <= $endMonth; $i++) {
            $monthName = $months[$i];
            $monthFolder = Folder::create([
                'name' => $monthName,
                'created_by' => $user->id,
                'parent_id' => $yearFolder->id,
                'company_id' => $companyId,
                'is_public' => false,
                'allow_uploads' => false,
                'path' => $yearFolder->path . '/' . $monthName,
            ]);
            
            // Associate the folder with the user
            $monthFolder->users()->attach($user->id);
            
            // Create category folders for each month
            $this->createCategoryFolders($user, $monthFolder, $companyId);
        }
    }
    
    /**
     * Create category folders for a month
     * 
     * @param User $user
     * @param Folder $monthFolder
     * @param int $companyId
     * @return void
     */
    private function createCategoryFolders(User $user, Folder $monthFolder, int $companyId)
    {
        $categories = ['Income', 'Expense', 'Banks', 'Other'];
        
        foreach ($categories as $category) {
            $categoryFolder = Folder::create([
                'name' => $category,
                'created_by' => $user->id,
                'parent_id' => $monthFolder->id,
                'company_id' => $companyId,
                'is_public' => false,
                'allow_uploads' => true, // Category folders should allow uploads
                'path' => $monthFolder->path . '/' . $category,
            ]);
            
            // Associate the folder with the user
            $categoryFolder->users()->attach($user->id);
        }
    }

    /**
     * Remove and recreate all folders for a company
     * 
     * @param User $user
     * @param Company $company
     * @return void
     */
    public function removeAndRecreateCompanyFolders(User $user, Company $company)
    {
        // Delete all existing folders for the company
        Folder::where('company_id', $company->id)->delete();

        // Create new folder structure
        $this->createCompanyFolders($user, $company);
    }

    /**
     * Create next month's folders automatically
     * This should be called by a scheduled task
     */
    public function createNextMonthFolders()
    {
        try {
            $nextMonth = Carbon::now()->addMonth()->startOfMonth();
            
            // Get all companies
            $companies = Company::all();
            
            foreach ($companies as $company) {
                $user = User::find($company->user_id);
                if (!$user) continue;

                // Find or create year folder
                $yearFolder = Folder::firstOrCreate(
                    [
                        'name' => (string) $nextMonth->year,
                        'company_id' => $company->id,
                        'parent_id' => Folder::where('name', $company->name)
                            ->where('company_id', $company->id)
                            ->where('parent_id', null)
                            ->first()->id
                    ],
                    [
                        'created_by' => $user->id,
                        'is_public' => false,
                        'allow_uploads' => false,
                        'path' => '/' . $company->name . '/' . $nextMonth->year
                    ]
                );

                // Associate the folder with the user if it's new
                if ($yearFolder->wasRecentlyCreated) {
                    $yearFolder->users()->attach($user->id);
                }

                // Check if month folder already exists
                $monthName = $nextMonth->format('F');
                $existingMonthFolder = Folder::where('name', $monthName)
                    ->where('parent_id', $yearFolder->id)
                    ->first();

                if (!$existingMonthFolder) {
                    // Create the month folder and its categories
                    $this->createMonthFolders(
                        $user,
                        $yearFolder,
                        $company->id,
                        $nextMonth->month,
                        $nextMonth->month
                    );
                    
                    Log::info("Created folders for {$company->name} - {$monthName} {$nextMonth->year}");
                }
            }
        } catch (\Exception $e) {
            Log::error("Error creating next month folders: " . $e->getMessage());
            throw $e;
        }
    }
} 