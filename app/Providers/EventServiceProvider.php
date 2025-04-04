<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * The model observers for your application.
     *
     * @var array<string, array<int, class-string>>
     */
    protected $observers = [
        //
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        // Listen for the eloquent.created: Company event
        \App\Models\Company::created(function (\App\Models\Company $company) {
            // Skip if this is being run in a console command
            if (app()->runningInConsole()) {
                return;
            }
            
            // Get the user
            $user = \App\Models\User::find($company->user_id);
            
            // Create folder structure
            if ($user) {
                $folderService = app(\App\Services\FolderStructureService::class);
                $folderService->createCompanyFolders($user, $company);
            }
        });
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
} 