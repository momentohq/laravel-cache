<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Momento\Auth\EnvMomentoTokenProvider;
use Momento\Cache\CacheClient;
use Momento\Cache\Errors\InvalidArgumentError;
use Momento\Cache\Errors\UnknownError;
use Momento\Cache\MomentoServiceProvider;
use Momento\Cache\MomentoStore;
use Momento\Config\Configurations\Laptop;

abstract class BaseTest extends Orchestra\Testbench\TestCase
{
    protected static CacheClient $cacheClient;
    protected static string $cacheName;

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
     * @throws UnknownError
     */
    protected function setUp(): void
    {
        if (!getenv('MOMENTO_CACHE_NAME') || !getenv('MOMENTO_API_KEY')) {
            throw new UnknownError("Environment variables named MOMENTO_CACHE_NAME and MOMENTO_API_KEY must be set.");
        }
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

    /**
     * Setup before any test in the class runs.
     * @throws InvalidArgumentError
     */
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        $cacheName = getenv('MOMENTO_CACHE_NAME');
        $authProvider = new EnvMomentoTokenProvider('MOMENTO_API_KEY');
        $configuration = Laptop::latest();
        self::$cacheClient = new CacheClient($configuration, $authProvider, 60);
        self::$cacheName = $cacheName;
        $result = self::$cacheClient->createCache($cacheName);

        if ($result->asSuccess()) {
            error_log("Cache '$cacheName' created successfully.");
        } else if ($result->asAlreadyExists()) {
            error_log("Cache '$cacheName' already exists.");
        } else if ($result->asError()) {
            error_log("Error creating cache '$cacheName': " . $result->asError()->message());
            throw new RuntimeException("Error creating cache '$cacheName': " . $result->asError()->message());
        }
    }

    /**
     * Clean up after all tests in the class have run.
     */
    public static function tearDownAfterClass(): void
    {
        parent::tearDownAfterClass();
        $result = self::$cacheClient->deleteCache(self::$cacheName);

        if ($result->asSuccess()) {
            error_log("Cache '" . self::$cacheName . "' deleted successfully.");
        } else if ($result->asError()) {
            error_log("Error deleting cache '" . self::$cacheName . "': " . $result->asError()->message());
        }
    }
}
