<?php

namespace Momento\Cache;

use Illuminate\Cache\TaggedCache;

class MomentoTaggedCache extends TaggedCache
{

    public function put($key, $value, $ttl = null)
    {
        $tags = $this->tags->getNames();
        $cacheName = $this->store->getCacheName();
        foreach ($tags as $tag) {
            $response = $this->store->setAdd($cacheName, $tag, $key, false, $ttl);
            if (!$response) {
                return false;
            }
        }
        $putResponse = $this->store->put($key, $value, $ttl);
        if (!$putResponse) {
            return false;
        }
        return true;
    }

    public function get($key, $default = null): mixed
    {
        $tags = $this->tags->getNames();
        $cacheName = $this->store->getCacheName();
        $value = null;
        foreach ($tags as $tag) {
            $keys = $this->store->setFetch($cacheName, $tag);
            foreach ($keys as $k) {
                if ($k == $key) {
                    $value = $this->store->get($key);
                }
            }
        }
        return $value;
    }
}
