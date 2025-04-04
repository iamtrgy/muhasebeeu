<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class ViewComponentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Admin components
        Blade::component('layouts.admin', 'admin-layout');
        Blade::component('components.admin.page-header', 'admin.page-header');
        Blade::component('components.admin.sidebar', 'admin.sidebar');
        
        // Accountant components
        // Reuse admin layout for accountant to maintain consistency
        Blade::component('layouts.admin', 'accountant-layout');
        Blade::component('components.admin.page-header', 'page-header');
    }
}
