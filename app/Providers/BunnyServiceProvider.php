<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Storage;
use App\Services\BunnyAdapter;
use Illuminate\Support\Facades\Log;

class BunnyServiceProvider extends ServiceProvider
{
    public function register()
    {
        Storage::extend('bunny', function ($app, $config) {
            Log::debug('BunnyServiceProvider extending storage with config:', $config);
            return new BunnyAdapter($config);
        });
    }

    public function boot()
    {
        //
    }
}
