<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Laravel\Cashier\Cashier;
use App\View\Components\Sidebar\AdminSidebar;
use App\View\Components\Sidebar\AccountantSidebar;
use App\View\Components\Sidebar\UserSidebar;
use App\View\Components\UnifiedHeader;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app['router']->aliasMiddleware('admin', \App\Http\Middleware\AdminMiddleware::class);
        
        // Configure Cashier to keep past due subscriptions active
        Cashier::keepPastDueSubscriptionsActive();
        
        // Register sidebar components
        Blade::component('sidebar-admin', AdminSidebar::class);
        Blade::component('sidebar-accountant', AccountantSidebar::class);
        Blade::component('sidebar-user', UserSidebar::class);
        
        // Register unified header component
        Blade::component('unified-header', UnifiedHeader::class);
    }
}
