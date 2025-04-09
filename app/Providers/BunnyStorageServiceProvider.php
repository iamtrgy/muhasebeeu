<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use League\Flysystem\Filesystem;
use App\Services\BunnyAdapter;

class BunnyStorageServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Storage::extend('bunny', function ($app, $config) {
            Log::debug('BunnyServiceProvider extending storage with config:', $config);
            
            $adapter = new BunnyAdapter($config);
            return new Filesystem($adapter);
        });
    }

    public function register()
    {
        //
    }
} 