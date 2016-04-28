<?php

namespace App\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class LeancloudServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('LeancloudService', function () {
            return new \App\Services\Leancloud(
                Config::get('leancloud.appId'),
                Config::get('leancloud.appKey')
            );
        });
    }
}
