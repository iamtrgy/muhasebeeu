<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\TaxCalendarTask;

class ViewComposerServiceProvider extends ServiceProvider
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
        // Share pending review count with accountant views
        View::composer(['layouts.accountant-sidebar', 'accountant.*'], function ($view) {
            if (auth()->check() && auth()->user()->is_accountant) {
                $pendingReviewCount = TaxCalendarTask::where('status', 'under_review')
                    ->whereHas('company', function ($query) {
                        $query->whereIn('id', auth()->user()->assignedCompanies()->pluck('companies.id'));
                    })
                    ->count();
                
                $view->with('pendingReviewCount', $pendingReviewCount);
            }
        });
    }
} 