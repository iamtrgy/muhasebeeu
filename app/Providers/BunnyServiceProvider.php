<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Filesystem;
use App\Services\BunnyAdapter;

class BunnyServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Storage::extend('bunny', function ($app, $config) {
            $adapter = new BunnyAdapter(
                $config['key'],
                $config['zone'],
                $config['region'],
                $config['url']
            );

            return new Filesystem($adapter);
        });
    }
}
