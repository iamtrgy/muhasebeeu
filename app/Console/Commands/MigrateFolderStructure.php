<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Company;
use App\Models\Folder;
use App\Models\File;
use App\Services\FolderStructureService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MigrateFolderStructure extends Command
{
    protected $signature = 'folders:migrate-structure {--company=} {--dry-run}';
    protected $description = 'Migrate from old folder structure to new Banks/Invoices/Documents structure';

    protected $folderService;

    public function __construct(FolderStructureService $folderService)
    {
        parent::__construct();
        $this->folderService = $folderService;
    }

    public function handle()
    {
        $isDryRun = $this->option('dry-run');
        $companyId = $this->option('company');

        if ($isDryRun) {
            $this->info('Running in DRY RUN mode - no changes will be made');
        }

        // Get companies to migrate
        $companies = $companyId 
            ? Company::where('id', $companyId)->get()
            : Company::all();

        $this->info("Found {$companies->count()} companies to process");

        foreach ($companies as $company) {
            $this->info("\nProcessing company: {$company->name} (ID: {$company->id})");
            
            try {
                DB::beginTransaction();
                
                // Check if new structure already exists
                $rootFolder = Folder::where('name', $company->name)
                    ->where('company_id', $company->id)
                    ->where('parent_id', null)
                    ->first();

                if (!$rootFolder) {
                    $this->error("Root folder not found for company {$company->name}");
                    DB::rollback();
                    continue;
                }

                // Check if new structure exists
                $hasNewStructure = Folder::where('parent_id', $rootFolder->id)
                    ->whereIn('name', ['Banks', 'Invoices', 'Documents'])
                    ->exists();

                if (!$hasNewStructure) {
                    $this->info("Creating new folder structure...");
                    if (!$isDryRun) {
                        $user = $company->user;
                        $this->folderService->createMainCategoryFolders($user, $rootFolder, $company);
                    }
                    $this->info("✓ New folder structure created");
                } else {
                    $this->info("New folder structure already exists");
                }

                // Migrate files from old structure
                $this->migrateFiles($company, $rootFolder, $isDryRun);

                if (!$isDryRun) {
                    DB::commit();
                    $this->info("✓ Migration completed for {$company->name}");
                } else {
                    DB::rollback();
                    $this->info("✓ Dry run completed for {$company->name}");
                }

            } catch (\Exception $e) {
                DB::rollback();
                $this->error("Error processing company {$company->name}: " . $e->getMessage());
                Log::error("Folder migration error", [
                    'company_id' => $company->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }

        $this->info("\nMigration process completed!");
    }

    protected function migrateFiles($company, $rootFolder, $isDryRun)
    {
        // Get new structure folders
        $banksFolder = Folder::where('parent_id', $rootFolder->id)
            ->where('name', 'Banks')
            ->first();
            
        $invoicesFolder = Folder::where('parent_id', $rootFolder->id)
            ->where('name', 'Invoices')
            ->first();
            
        $documentsFolder = Folder::where('parent_id', $rootFolder->id)
            ->where('name', 'Documents')
            ->first();

        if (!$banksFolder || !$invoicesFolder || !$documentsFolder) {
            $this->warn("New folder structure not complete, skipping file migration");
            return;
        }

        // Get Income and Expense subfolders
        $incomeFolder = Folder::where('parent_id', $invoicesFolder->id)
            ->where('name', 'Income')
            ->first();
            
        $expenseFolder = Folder::where('parent_id', $invoicesFolder->id)
            ->where('name', 'Expense')
            ->first();

        // Find all files in old structure
        $oldStructureFolders = Folder::where('company_id', $company->id)
            ->where('parent_id', '!=', null)
            ->whereIn('name', ['Income', 'Expense', 'Banks', 'Other'])
            ->where(function($query) use ($banksFolder, $invoicesFolder, $documentsFolder) {
                // Exclude folders that are already in new structure
                $query->where('parent_id', '!=', $banksFolder->id)
                    ->where('parent_id', '!=', $invoicesFolder->id)
                    ->where('parent_id', '!=', $documentsFolder->id);
            })
            ->get();

        $this->info("Found {$oldStructureFolders->count()} old category folders");

        $filesMoved = 0;
        
        foreach ($oldStructureFolders as $oldFolder) {
            $files = File::where('folder_id', $oldFolder->id)->get();
            
            if ($files->count() == 0) {
                continue;
            }

            $this->info("  Processing {$files->count()} files in {$oldFolder->full_path}");

            // Determine target folder based on old folder name and parent structure
            $monthFolder = $oldFolder->parent; // Should be month folder
            $yearFolder = $monthFolder ? $monthFolder->parent : null;
            
            if (!$yearFolder) {
                $this->warn("    Cannot determine year/month for folder {$oldFolder->name}");
                continue;
            }

            $year = $yearFolder->name;
            $month = $monthFolder->name;

            foreach ($files as $file) {
                $targetFolder = null;

                switch ($oldFolder->name) {
                    case 'Banks':
                        // Create year/month structure under Banks
                        $targetFolder = $this->ensureYearMonthFolder($banksFolder, $year, $month, $company);
                        break;
                        
                    case 'Income':
                        // Create year/month structure under Invoices/Income
                        $targetFolder = $this->ensureYearMonthFolder($incomeFolder, $year, $month, $company);
                        break;
                        
                    case 'Expense':
                        // Create year/month structure under Invoices/Expense
                        $targetFolder = $this->ensureYearMonthFolder($expenseFolder, $year, $month, $company);
                        break;
                        
                    case 'Other':
                        // Move to Documents/Other
                        $otherFolder = Folder::where('parent_id', $documentsFolder->id)
                            ->where('name', 'Other')
                            ->first();
                        $targetFolder = $otherFolder;
                        break;
                }

                if ($targetFolder) {
                    $this->info("    Moving {$file->original_name} to {$targetFolder->full_path}");
                    if (!$isDryRun) {
                        $file->update(['folder_id' => $targetFolder->id]);
                    }
                    $filesMoved++;
                }
            }
        }

        $this->info("Total files to be moved: {$filesMoved}");
    }

    protected function ensureYearMonthFolder($parentFolder, $year, $month, $company)
    {
        if (!$parentFolder) {
            return null;
        }

        // Ensure year folder exists
        $yearFolder = Folder::firstOrCreate(
            [
                'name' => $year,
                'parent_id' => $parentFolder->id,
                'company_id' => $company->id
            ],
            [
                'created_by' => $company->user_id,
                'is_public' => false,
                'allow_uploads' => false,
                'path' => $parentFolder->path . '/' . $year
            ]
        );

        // Ensure month folder exists
        $monthFolder = Folder::firstOrCreate(
            [
                'name' => $month,
                'parent_id' => $yearFolder->id,
                'company_id' => $company->id
            ],
            [
                'created_by' => $company->user_id,
                'is_public' => false,
                'allow_uploads' => true,
                'path' => $yearFolder->path . '/' . $month
            ]
        );

        return $monthFolder;
    }
}