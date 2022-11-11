<?php

namespace Momento\Cache;

use Illuminate\Cache\TaggedCache;
use Momento\Cache\Errors\UnknownError;

class MomentoTaggedCache extends TaggedCache
{
    const maxTtl = 86400;

    public function put($key, $value, $ttl = null): bool
    {
        $tags = $this->tags->getNames();
        if (!self::validateTags($tags)) {
            return false;
        }
        $cacheName = $this->store->getCacheName();
        $newKey = self::createNewKey($tags, $key);
        $hashedKey = hash("sha256", $newKey);
        foreach ($tags as $tag) {
            $hashedKeyResponse = $this->store->setAdd($cacheName, $tag, $hashedKey, true, self::maxTtl);
            if (!$hashedKeyResponse) {
                return false;
            }
        }
        $putResponse = $this->store->put($hashedKey, $value, $ttl);
        if (!$putResponse) {
            return false;
        }
        return true;
    }

    public function get($key, $default = null): mixed
    {
        $tags = $this->tags->getNames();
        if (!self::validateTags($tags)) {
            return null;
        }
        $cacheName = $this->store->getCacheName();
        $value = null;
        $newKey = self::createNewKey($tags, $key);
        $hashedKey = hash("sha256", $newKey);
        foreach ($tags as $tag) {
            $hashedKeys = $this->store->setFetch($cacheName, $tag);
            if (is_null($hashedKeys)) {
                return $value;
            } else {
                foreach ($hashedKeys as $hk) {
                    if ($hk == $hashedKey) {
                        $value = $this->store->get($hashedKey);
                        break;
                    }
                }
            }
        }
        return $value;
    }

    /**
     * @throws UnknownError
     */
    public function flush()
    {
        throw new UnknownError("flush operations is currently not supported.");
    }

    public static function createNewKey($tags, $key): string
    {
        return join("-", $tags) . "-${key}";
    }

    public static function validateTags($tags): bool
    {
        if (empty($tags)) {
            return false;
        }
        return true;
    }
}
