<?php

namespace Momento\Cache;

use Illuminate\Cache\TaggableStore;
use Illuminate\Cache\TagSet;
use Momento\Auth\EnvMomentoTokenProvider;
use Momento\Cache\Errors\UnknownError;
use Momento\Cache\SimpleCacheClient;

class MomentoStore extends TaggableStore
{
    protected SimpleCacheClient $client;
    protected string $cacheName;

    public function __construct(string $cacheName, int $defaultTtl)
    {
        $authProvider = new EnvMomentoTokenProvider('MOMENTO_AUTH_TOKEN');
        $this->client = new SimpleCacheClient($authProvider, $defaultTtl);
        $this->cacheName = $cacheName;
        $this->client->createCache($cacheName);
    }

    public function get($key)
    {
        $result = $this->client->get($this->cacheName, $key);
        if ($result->asHit()) {
            return $result->asHit()->value();
        } elseif ($result->asMiss()) {
            return null;
        }
    }

    public function many(array $keys)
    {
        throw new UnknownError("many operations is currently not supported.");
    }

    public function put($key, $value, $seconds)
    {
        $result = $this->client->set($this->cacheName, $key, $value, $seconds);
        if ($result->asSuccess()) {
            return true;
        } else {
            return false;
        }
    }

    public function putMany(array $values, $seconds)
    {
        throw new UnknownError("putMany operations is currently not supported.");
    }

    public function increment($key, $value = 1)
    {
        return false;
    }

    public function decrement($key, $value = 1)
    {
        throw new UnknownError("decrement operations is currently not supported.");
    }

    public function forever($key, $value)
    {
        throw new UnknownError("forever operations is currently not supported.");
    }

    public function forget($key)
    {
        throw new UnknownError("forget operations is currently not supported.");
    }

    public function flush()
    {
        throw new UnknownError("flush operations is currently not supported.");
    }

    public function getPrefix()
    {
    }

    public function setAdd(string $cacheName, string $setName, string $element, bool $refreshTtl, ?int $ttlSeconds = null)
    {
        $result = $this->client->setAdd($cacheName, $setName, $element, $refreshTtl, $ttlSeconds);
        if ($result->asSuccess()) {
            return true;
        } else {
            return false;
        }
    }

    public function setFetch(string $cacheName, string $setName)
    {
        $result = $this->client->setFetch($cacheName, $setName);
        if ($result->asHit()) {
            return $result->asHit()->stringSet();
        } else {
            return null;
        }
    }

    public function tags($names)
    {
        return new MomentoTaggedCache(
            $this, new TagSet($this, is_array($names) ? $names : func_get_args())
        );
    }

    public function getCacheName()
    {
        return $this->cacheName;
    }

}
