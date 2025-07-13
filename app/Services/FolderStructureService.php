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

            // Create main category folders: Banks, Invoices, Documents
            $this->createMainCategoryFolders($user, $rootFolder, $company);
            
        } catch (\Exception $e) {
            Log::error("Error creating folder structure: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Create main category folders: Banks, Invoices, Documents
     * 
     * @param User $user
     * @param Folder $rootFolder
     * @param Company $company
     * @return void
     */
    public function createMainCategoryFolders(User $user, Folder $rootFolder, Company $company)
    {
        // 1. Create Banks folder structure
        $banksFolder = Folder::create([
            'name' => 'Banks',
            'created_by' => $user->id,
            'parent_id' => $rootFolder->id,
            'company_id' => $company->id,
            'is_public' => false,
            'allow_uploads' => false,
            'path' => $rootFolder->path . '/Banks',
        ]);
        $banksFolder->users()->attach($user->id);
        
        // Create year/month structure for Banks
        $this->createYearMonthStructure($user, $banksFolder, $company, true);
        
        // 2. Create Invoices folder structure
        $invoicesFolder = Folder::create([
            'name' => 'Invoices',
            'created_by' => $user->id,
            'parent_id' => $rootFolder->id,
            'company_id' => $company->id,
            'is_public' => false,
            'allow_uploads' => false,
            'path' => $rootFolder->path . '/Invoices',
        ]);
        $invoicesFolder->users()->attach($user->id);
        
        // Create Income/Expense subfolders
        $incomeFolder = Folder::create([
            'name' => 'Income',
            'created_by' => $user->id,
            'parent_id' => $invoicesFolder->id,
            'company_id' => $company->id,
            'is_public' => false,
            'allow_uploads' => false,
            'path' => $invoicesFolder->path . '/Income',
        ]);
        $incomeFolder->users()->attach($user->id);
        $this->createYearMonthStructure($user, $incomeFolder, $company, true);
        
        $expenseFolder = Folder::create([
            'name' => 'Expense',
            'created_by' => $user->id,
            'parent_id' => $invoicesFolder->id,
            'company_id' => $company->id,
            'is_public' => false,
            'allow_uploads' => false,
            'path' => $invoicesFolder->path . '/Expense',
        ]);
        $expenseFolder->users()->attach($user->id);
        $this->createYearMonthStructure($user, $expenseFolder, $company, true);
        
        // 3. Create Documents folder structure
        $documentsFolder = Folder::create([
            'name' => 'Documents',
            'created_by' => $user->id,
            'parent_id' => $rootFolder->id,
            'company_id' => $company->id,
            'is_public' => false,
            'allow_uploads' => false,
            'path' => $rootFolder->path . '/Documents',
        ]);
        $documentsFolder->users()->attach($user->id);
        
        // Create document categories
        $documentCategories = ['Contracts', 'Tax Documents', 'Legal', 'Other'];
        foreach ($documentCategories as $category) {
            $categoryFolder = Folder::create([
                'name' => $category,
                'created_by' => $user->id,
                'parent_id' => $documentsFolder->id,
                'company_id' => $company->id,
                'is_public' => false,
                'allow_uploads' => true, // Allow uploads in document categories
                'path' => $documentsFolder->path . '/' . $category,
            ]);
            $categoryFolder->users()->attach($user->id);
        }
    }
    
    /**
     * Create year/month folder structure
     * 
     * @param User $user
     * @param Folder $parentFolder
     * @param Company $company
     * @param bool $allowUploadsInMonths
     * @return void
     */
    private function createYearMonthStructure(User $user, Folder $parentFolder, Company $company, bool $allowUploadsInMonths = false)
    {
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
                'parent_id' => $parentFolder->id,
                'company_id' => $company->id,
                'is_public' => false,
                'allow_uploads' => false,
                'path' => $parentFolder->path . '/' . $year,
            ]);
            
            // Associate the folder with the user
            $yearFolder->users()->attach($user->id);
            
            // For first year, start from foundation month
            $monthStart = ($year == $startYear) ? $startMonth : 1;
            $monthEnd = 12;
            
            // Create month folders with upload permission
            $this->createMonthFolders($user, $yearFolder, $company->id, $monthStart, $monthEnd, $allowUploadsInMonths);
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
     * @param bool $allowUploads
     * @return void
     */
    private function createMonthFolders(User $user, Folder $yearFolder, int $companyId, int $startMonth = 1, int $endMonth = 12, bool $allowUploads = false)
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
                'allow_uploads' => $allowUploads, // Use the parameter to control uploads
                'path' => $yearFolder->path . '/' . $monthName,
            ]);
            
            // Associate the folder with the user
            $monthFolder->users()->attach($user->id);
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
                try {
                    $user = User::find($company->user_id);
                    if (!$user) {
                        Log::warning("Skipping folder creation for company {$company->id}: User not found");
                        continue;
                    }

                    // Find root company folder
                    $rootFolder = Folder::where('name', $company->name)
                        ->where('company_id', $company->id)
                        ->where('parent_id', null)
                        ->first();

                    if (!$rootFolder) {
                        Log::warning("Skipping folder creation for company {$company->id}: Root folder not found");
                        continue;
                    }

                    // Create next month folders for each main category
                    $mainCategories = ['Banks', 'Invoices'];
                    
                    foreach ($mainCategories as $category) {
                        $categoryFolder = Folder::where('name', $category)
                            ->where('company_id', $company->id)
                            ->where('parent_id', $rootFolder->id)
                            ->first();
                            
                        if (!$categoryFolder) {
                            Log::warning("Category folder {$category} not found for company {$company->id}");
                            continue;
                        }
                        
                        if ($category === 'Banks') {
                            // Create directly under Banks
                            $this->createMonthInFolder($user, $categoryFolder, $company, $nextMonth);
                        } else if ($category === 'Invoices') {
                            // Create under Income and Expense subfolders
                            $subCategories = ['Income', 'Expense'];
                            foreach ($subCategories as $subCategory) {
                                $subFolder = Folder::where('name', $subCategory)
                                    ->where('company_id', $company->id)
                                    ->where('parent_id', $categoryFolder->id)
                                    ->first();
                                    
                                if ($subFolder) {
                                    $this->createMonthInFolder($user, $subFolder, $company, $nextMonth);
                                }
                            }
                        }
                    }
                    
                    Log::info("Processed next month folders for company {$company->name}");
                } catch (\Exception $e) {
                    Log::error("Error processing company {$company->id}: " . $e->getMessage());
                    continue;
                }
            }
        } catch (\Exception $e) {
            Log::error("Error in createNextMonthFolders: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Create month folder in a specific parent folder
     */
    private function createMonthInFolder(User $user, Folder $parentFolder, Company $company, Carbon $month)
    {
        // Find or create year folder
        $yearFolder = Folder::firstOrCreate(
            [
                'name' => (string) $month->year,
                'company_id' => $company->id,
                'parent_id' => $parentFolder->id,
            ],
            [
                'created_by' => $user->id,
                'is_public' => false,
                'allow_uploads' => false,
                'path' => $parentFolder->path . '/' . $month->year,
            ]
        );

        // Associate the folder with the user if it's new
        if ($yearFolder->wasRecentlyCreated) {
            $yearFolder->users()->attach($user->id);
        }

        // Check if month folder already exists
        $monthName = $month->format('F');
        
        $existingMonthFolder = Folder::where('name', $monthName)
            ->where('parent_id', $yearFolder->id)
            ->where('company_id', $company->id)
            ->first();

        if (!$existingMonthFolder) {
            // Create the month folder
            $monthFolder = Folder::create([
                'name' => $monthName,
                'created_by' => $user->id,
                'parent_id' => $yearFolder->id,
                'company_id' => $company->id,
                'is_public' => false,
                'allow_uploads' => true, // Allow uploads in month folders
                'path' => $yearFolder->path . '/' . $monthName,
            ]);
            
            $monthFolder->users()->attach($user->id);
            
            Log::info("Created month folder: {$parentFolder->name}/{$month->year}/{$monthName}");
        }
    }
} 