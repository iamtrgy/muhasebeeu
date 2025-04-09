<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Storage;
use App\Services\BunnyAdapter;

class BunnyServiceProvider extends ServiceProvider
{
    public function register()
    {
        Storage::extend('bunny', function ($app, $config) {
            return new BunnyAdapter($config);
        });
    }

    public function boot()
    {
        //
    }
}
