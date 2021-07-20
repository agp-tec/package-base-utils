<?php

namespace Agp\BaseUtils;

use Agp\BaseUtils\Commands\InstallServiceJS;
use Illuminate\Support\ServiceProvider;

class AgpBaseUtilsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/Routes/web.php');

        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallServiceJS::class,
            ]);
        }

        $this->publishes([
            __DIR__.'/config/base-utils.php' => config_path('base-utils.php')
        ], 'base-utils-config');

    }

    public function register()
    {
        $this->loadViewsFrom(__DIR__ . '/Views', 'BaseUtils');
    }
}
