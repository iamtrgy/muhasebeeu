<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Filesystem;
use App\Services\BunnyAdapter;

class BunnyStorageServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Storage::extend('bunny', function ($app, $config) {
            $adapter = new BunnyAdapter(
                $config['storage_zone_name'],
                $config['api_key'],
                $config['region'] ?? 'de',
                $config['hostname'] ?? null
            );

            return new Filesystem($adapter);
        });
    }

    public function register()
    {
        //
    }
} 