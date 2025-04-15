<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\User;
use App\Services\FolderStructureService;
use Illuminate\Console\Command;

class RecreateCompanyFolders extends Command
{
    protected $signature = 'company:recreate-folders {company_name}';
    protected $description = 'Recreate folder structure for a company';

    public function handle(FolderStructureService $folderService)
    {
        $companyName = $this->argument('company_name');
        $company = Company::where('name', $companyName)->first();
        
        if (!$company) {
            $this->error("Company not found: {$companyName}");
            return 1;
        }
        
        $user = User::find($company->user_id);
        if (!$user) {
            $this->error("User not found for company: {$companyName}");
            return 1;
        }
        
        try {
            $folderService->removeAndRecreateCompanyFolders($user, $company);
            $this->info("Successfully recreated folder structure for {$companyName}");
            return 0;
        } catch (\Exception $e) {
            $this->error("Error recreating folders: " . $e->getMessage());
            return 1;
        }
    }
} 