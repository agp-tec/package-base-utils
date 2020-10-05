<?php

namespace Agp\BaseUtils;

use Illuminate\Support\ServiceProvider;

class AgpBaseUtilsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/baseutils.php' => config_path('baseutils.php'),
        ], 'config');
    }

    public function register()
    {
    }
}
