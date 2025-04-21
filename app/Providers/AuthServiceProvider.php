<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Folder;
use App\Policies\FolderPolicy;
use App\Models\File;
use App\Policies\FilePolicy;
use App\Models\TaxCalendarTask;
use App\Policies\TaxCalendarTaskPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Folder::class => FolderPolicy::class,
        File::class => FilePolicy::class,
        TaxCalendarTask::class => TaxCalendarTaskPolicy::class,
    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
} 