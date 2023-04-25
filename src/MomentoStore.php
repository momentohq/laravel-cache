<?php

namespace Momento\Cache;

use Illuminate\Cache\TaggableStore;
use Illuminate\Cache\TagSet;
use Momento\Auth\EnvMomentoTokenProvider;
use Momento\Cache\Errors\UnknownError;
use Momento\Config\Configurations\Laptop;
use Momento\Requests\CollectionTtl;

class MomentoStore extends TaggableStore
{
    protected CacheClient $client;
    protected string $cacheName;

    public function __construct(string $cacheName, int $defaultTtl)
    {
        $authProvider = new EnvMomentoTokenProvider('MOMENTO_AUTH_TOKEN');
        $configuration = Laptop::latest();
        $this->client = new CacheClient($configuration, $authProvider, $defaultTtl);
        $this->cacheName = $cacheName;
        $this->client->createCache($cacheName);
    }

    public function get($key): ?string
    {
        $result = $this->client->get($this->cacheName, $key);
        if ($result->asHit()) {
            return $result->asHit()->valueString();
        } elseif ($result->asMiss()) {
            return null;
        }
        return null;
    }

    /**
     * @throws UnknownError
     */
    public function many(array $keys)
    {
        throw new UnknownError("many operations is currently not supported.");
    }

    public function put($key, $value, $seconds): bool
    {
        $result = $this->client->set($this->cacheName, $key, $value, $seconds);
        if ($result->asSuccess()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @throws UnknownError
     */
    public function putMany(array $values, $seconds)
    {
        throw new UnknownError("putMany operations is currently not supported.");
    }

    public function increment($key, $value = 1)
    {
        $incrResult = $this->client->increment($this->cacheName, $key, $value);
        if ($incrResult->asSuccess()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @throws UnknownError
     */
    public function decrement($key, $value = 1)
    {
        throw new UnknownError("decrement operations is currently not supported.");
    }

    /**
     * @throws UnknownError
     */
    public function forever($key, $value)
    {
        throw new UnknownError("forever operations is currently not supported.");
    }

    public function forget($key): bool
    {
        $result = $this->client->delete($this->cacheName, $key);
        if ($result->asSuccess()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @throws UnknownError
     */
    public function flush()
    {
        throw new UnknownError("flush operations is currently not supported.");
    }

    public function getPrefix()
    {
    }

    public function setAddElement(string $setName, string $element, CollectionTtl $collectionTtl): bool
    {
        $result = $this->client->setAddElement($this->cacheName, $setName, $element, $collectionTtl);
        if ($result->asSuccess()) {
            return true;
        } else {
            return false;
        }
    }

    public function setFetch(string $setName): ?array
    {
        $result = $this->client->setFetch($this->cacheName, $setName);
        if ($result->asHit()) {
            return $result->asHit()->valuesArray();
        } else {
            return null;
        }
    }

    public function setDelete(string $setName): bool
    {
        $result = $this->client->delete($this->cacheName, $setName);
        if ($result->asSuccess()) {
            return true;
        } else {
            return false;
        }
    }

    public function tags($names): MomentoTaggedCache
    {
        return new MomentoTaggedCache(
            $this, new TagSet($this, is_array($names) ? $names : func_get_args())
        );
    }
}
