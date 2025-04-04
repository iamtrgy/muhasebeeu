<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's controller namespace.
     *
     * This allows automatic controller resolution to work as in Laravel 7.
     *
     * @var string|null
     */
    protected $namespace = null; // Using ::class syntax, so no namespace needed
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/user/dashboard';
    
    /**
     * The path to the "home" route for admin users.
     *
     * @var string
     */
    public const ADMIN_HOME = '/admin/dashboard';
    
    /**
     * The path to the "home" route for accountant users.
     *
     * @var string
     */
    public const ACCOUNTANT_HOME = '/accountant/dashboard';
    
    /**
     * Get the redirect path based on user role.
     *
     * @param \App\Models\User $user
     * @return string
     */
    public static function getHomeForUser($user)
    {
        if ($user->is_admin) {
            return static::ADMIN_HOME;
        } elseif ($user->is_accountant) {
            return static::ACCOUNTANT_HOME;
        } else {
            return static::HOME;
        }
    }

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
} 