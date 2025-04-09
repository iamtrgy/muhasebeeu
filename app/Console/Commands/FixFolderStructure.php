<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Folder;
use App\Models\Company;
use App\Models\User;
use App\Services\FolderStructureService;

class FixFolderStructure extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'folders:fix';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix folder structure inconsistencies';

    /**
     * @var FolderStructureService
     */
    protected $folderService;

    /**
     * Create a new command instance.
     */
    public function __construct(FolderStructureService $folderService)
    {
        parent::__construct();
        $this->folderService = $folderService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting folder structure fix...');

        DB::transaction(function () {
            // Get all companies
            $companies = Company::all();
            
            foreach ($companies as $company) {
                $this->info("Processing company: {$company->name}");
                
                // Get the company owner or first user
                $user = User::where('id', $company->owner_id)
                    ->orWhereHas('companies', function($query) use ($company) {
                        $query->where('companies.id', $company->id);
                    })
                    ->first();
                
                if (!$user) {
                    $this->warn("No users found for company {$company->name}, skipping...");
                    continue;
                }

                try {
                    $this->folderService->removeAndRecreateCompanyFolders($user, $company);
                    $this->info("Successfully recreated folder structure for {$company->name}");
                } catch (\Exception $e) {
                    $this->error("Error processing company {$company->name}: " . $e->getMessage());
                }
            }
        });

        $this->info('Folder structure fix completed successfully.');

        // Show current folder statistics
        $this->table(
            ['Status', 'Count'],
            [
                ['Total Folders', Folder::withTrashed()->count()],
                ['Active Folders', Folder::count()],
                ['Deleted Folders', Folder::onlyTrashed()->count()],
            ]
        );
    }
}
