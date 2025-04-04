<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Company;
use App\Services\FolderStructureService;

class CreateCompanyFolders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'company:create-folders {user_id? : The ID of the user to create folders for} {--all : Create folders for all users}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create standard folder structure for company documents';

    /**
     * The folder structure service
     */
    protected $folderService;

    /**
     * Create a new command instance.
     *
     * @return void
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
        $userId = $this->argument('user_id');
        $all = $this->option('all');

        if ($all) {
            $this->createFoldersForAllUsers();
        } elseif ($userId) {
            $this->createFoldersForUser($userId);
        } else {
            $this->error('Please provide a user ID or use the --all option');
            return 1;
        }

        return 0;
    }

    /**
     * Create folders for a specific user
     */
    private function createFoldersForUser($userId)
    {
        $user = User::find($userId);
        
        if (!$user) {
            $this->error("User with ID {$userId} not found");
            return;
        }
        
        $companies = Company::where('user_id', $user->id)->get();
        
        if ($companies->isEmpty()) {
            $this->info("No companies found for user {$user->name} (ID: {$user->id})");
            return;
        }
        
        $this->info("Creating folders for user {$user->name} (ID: {$user->id})");
        
        foreach ($companies as $company) {
            $this->info("Creating folders for company: {$company->name}");
            $this->folderService->createCompanyFolders($user, $company);
        }
        
        $this->info("Folders created successfully for user {$user->name}");
    }
    
    /**
     * Create folders for all users
     */
    private function createFoldersForAllUsers()
    {
        $users = User::whereHas('companies')->get();
        
        if ($users->isEmpty()) {
            $this->info("No users with companies found");
            return;
        }
        
        $this->info("Creating folders for {$users->count()} users with companies");
        
        $bar = $this->output->createProgressBar($users->count());
        $bar->start();
        
        foreach ($users as $user) {
            $companies = Company::where('user_id', $user->id)->get();
            
            foreach ($companies as $company) {
                $this->folderService->createCompanyFolders($user, $company);
            }
            
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine();
        $this->info("Folders created successfully for all users");
    }
}
