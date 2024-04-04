<?php

namespace Momento\Cache;

use Illuminate\Cache\TaggableStore;
use Illuminate\Cache\TagSet;
use Momento\Auth\EnvMomentoTokenProvider;
use Momento\Cache\Errors\InvalidArgumentError;
use Momento\Cache\Errors\UnknownError;
use Momento\Config\Configurations\Laptop;
use Momento\Requests\CollectionTtl;

class MomentoStore extends TaggableStore
{
    protected CacheClient $client;
    protected string $cacheName;

    /**
     * @throws InvalidArgumentError
     */
    public function __construct(string $cacheName, int $defaultTtl)
    {
        $authProvider = new EnvMomentoTokenProvider('MOMENTO_API_KEY');
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

    public function many(array $keys): ?array
    {
        $result = $this->client->getBatch($this->cacheName, $keys);
        if ($result->asSuccess()) {
            return $result->asSuccess()->values();
        } elseif ($result->asError()){
            return null;
        }
        return null;
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

    public function putMany(array $values, $seconds): bool
    {
        $result = $this->client->setBatch($this->cacheName, $values, $seconds);
        if ($result->asSuccess()) {
            return true;
        } elseif ($result->asError()) {
            return false;
        }
        return false;
    }

    public function increment($key, $value = 1): bool
    {
        $result = $this->client->increment($this->cacheName, $key, $value);
        if ($result->asSuccess()) {
            return true;
        } else {
            return false;
        }
    }

    public function decrement($key, $value = 1): bool
    {
        $result = $this->client->increment($this->cacheName, $key, $value * -1);
        if ($result->asSuccess()) {
            return true;
        } else {
            return false;
        }
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

    public function flush(): bool
    {

        $result = $this->client->flushCache($this->cacheName);
        if ($result->asSuccess()) {
            return true;
        } else {
            return false;
        }
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
