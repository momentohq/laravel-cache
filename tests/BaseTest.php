<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Momento\Cache\MomentoServiceProvider;
use Momento\Cache\MomentoStore;

abstract class BaseTest extends Orchestra\Testbench\TestCase
{
    /**
     * Get package providers.
     *
     * @param Application $app
     *
     * @return array<int, class-string<ServiceProvider>>
     */
    protected function getPackageProviders($app)
    {
        return [
            MomentoServiceProvider::class
        ];
    }

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();
        app('cache')->extend('momento', function ($app, $config) {
            $store = new MomentoStore($config['cache_name'], $config['default_ttl']);
            return app('cache')->repository($store);
        });
        $this->app['config']->set('cache.default', 'momento');
        $this->app['config']->set('cache.stores.momento',
            [
                'driver' => 'momento',
                'cache_name' => env('MOMENTO_CACHE_NAME'),
                'default_ttl' => 60
            ]
        );


    }

}