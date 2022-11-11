<?php

namespace Momento\Cache;

use Illuminate\Cache\TaggedCache;

class MomentoTaggedCache extends TaggedCache
{
    const maxTtl = 86400;

    public function put($key, $value, $ttl = null)
    {
        $tags = $this->tags->getNames();
        if (!self::validateTags($tags)) {
            return false;
        }
        $cacheName = $this->store->getCacheName();
        $newKey = self::createNewKey($tags, $key);
        $hashedKey = hash("sha256", $newKey);
        foreach ($tags as $tag) {
            $newKeyResponse = $this->store->setAdd($cacheName, $tag, $newKey, true, self::maxTtl);
            $hashedKeyResponse = $this->store->setAdd($cacheName, $tag, $hashedKey, true, self::maxTtl);
            if (!$newKeyResponse || !$hashedKeyResponse) {
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
            return false;
        }
        $cacheName = $this->store->getCacheName();
        $value = null;
        $newKey = self::createNewKey($tags, $key);
        $hashedKey = hash("sha256", $newKey);
        foreach ($tags as $tag) {
            $keys = $this->store->setFetch($cacheName, $tag);
            if (is_null($keys)) {
                return $value;
            } else {
                foreach ($keys as $k) {
                    if ($k == $hashedKey) {
                        $value = $this->store->get($hashedKey);
                        break;
                    }
                }
            }
        }
        return $value;
    }

    private function createNewKey($tags, $key): string
    {
        $newKey = "";
        if (count($tags) == 1) {
            return "${tags[0]}-${key}";
        } else {
            foreach ($tags as $index => $tag) {
                if ($index == 0) {
                    $newKey = "${tag}-";
                } else {
                    $newKey = "${newKey}${tag}-";
                }
            }
            return "${newKey}${key}";
        }
    }

    private function validateTags($tags): bool
    {
        if (empty($tags)) {
            return false;
        }
        return true;
    }
}
