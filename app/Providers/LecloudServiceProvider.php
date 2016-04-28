<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;

class LecloudServiceProvider extends ServiceProvider
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
        $this->app->singleton('LecloudService', function () {
            return new \App\Services\Lecloud(
                Config::get("lecloud.secretkey"),
                Config::get("lecloud.userId"),
                Config::get("lecloud.uu")
            );
        });
    }
}
