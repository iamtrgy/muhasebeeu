<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Services\FolderStructureService;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\ResetAdminPassword::class,
        Commands\RestoreAdmin::class,
        Commands\RecreateCompanyFolders::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Create next month's folders on the 25th of each month
        $schedule->call(function () {
            $folderService = app(FolderStructureService::class);
            $folderService->createNextMonthFolders();
        })->monthlyOn(25, '00:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
} 