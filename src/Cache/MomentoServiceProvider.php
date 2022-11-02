<?php

namespace Momento\Cache;

use Illuminate\Support\ServiceProvider;

class MomentoServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        app('cache')->extend('momento', function ($app, $config) {
            $store = new MomentoStore($config['cache_name'], $config['default_ttl']);
            return app('cache')->repository($store);
        });
    }
}
