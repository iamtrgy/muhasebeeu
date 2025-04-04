<?php

namespace App\Services;

use App\Models\Folder;
use App\Models\User;
use App\Models\Company;
use Carbon\Carbon;

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
        // Create root company folder
        $rootFolder = Folder::create([
            'name' => $company->name,
            'created_by' => $user->id,
            'parent_id' => null,
            'company_id' => $company->id,
            'is_public' => false,
            'allow_uploads' => true,
        ]);

        // Associate the folder with the user
        $rootFolder->users()->attach($user->id);

        // Determine the start year (company foundation year or current year if not available)
        $startYear = date('Y'); // Default to current year
        
        // If there's a foundation_date field, use that year
        if ($company->foundation_date) {
            $startYear = Carbon::parse($company->foundation_date)->year;
        }
        // If no foundation_date but there's a created_at timestamp, use that year
        elseif ($company->created_at) {
            $startYear = Carbon::parse($company->created_at)->year;
        }
        
        // Current year
        $currentYear = date('Y');
        
        // Create folders for each year from foundation to current year
        for ($year = $startYear; $year <= $currentYear; $year++) {
            $yearFolder = Folder::create([
                'name' => (string) $year,
                'created_by' => $user->id,
                'parent_id' => $rootFolder->id,
                'company_id' => $company->id,
                'is_public' => false,
                'allow_uploads' => true,
            ]);
            
            // Associate the folder with the user
            $yearFolder->users()->attach($user->id);
            
            // Create month folders for each year
            $this->createMonthFolders($user, $yearFolder, $company->id);
        }
    }
    
    /**
     * Create month folders for a year
     * 
     * @param User $user
     * @param Folder $yearFolder
     * @param int $companyId
     * @return void
     */
    private function createMonthFolders(User $user, Folder $yearFolder, int $companyId)
    {
        $months = [
            'January', 'February', 'March', 'April', 'May', 'June', 
            'July', 'August', 'September', 'October', 'November', 'December'
        ];
        
        foreach ($months as $month) {
            $monthFolder = Folder::create([
                'name' => $month,
                'created_by' => $user->id,
                'parent_id' => $yearFolder->id,
                'company_id' => $companyId,
                'is_public' => false,
                'allow_uploads' => true,
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
                'allow_uploads' => true,
            ]);
            
            // Associate the folder with the user
            $categoryFolder->users()->attach($user->id);
        }
    }
} 