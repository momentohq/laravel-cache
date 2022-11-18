<?php

namespace Momento\Cache;

use Illuminate\Cache\TaggedCache;
use Momento\Cache\Errors\UnknownError;

class MomentoTaggedCache extends TaggedCache
{
    public function put($key, $value, $ttl = null): bool
    {
        # Divide PHP_INT_MAX by 1000 so that when mxTtl is converted to milliseconds by ttlToMillis, the return value is still int not float.
        $MAX_TTL = intdiv(PHP_INT_MAX, 1000);
        $tags = $this->tags->getNames();
        if (!self::validateTags($tags)) {
            return false;
        }
        $newKey = self::createNewKey($tags, $key);
        $hashedKey = hash("sha256", $newKey);
        foreach ($tags as $tag) {
            $hashedKeyResponse = $this->store->setAddElement($tag, $hashedKey, true, $MAX_TTL);
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
        $newKey = self::createNewKey($tags, $key);
        $hashedKey = hash("sha256", $newKey);
        $getResponse = $this->store->get($hashedKey);
        if (is_null($getResponse)) {
            return $default;
        }
        return $getResponse;
    }

    /**
     * @throws UnknownError
     */
    public function flush(): bool
    {
        $tags = $this->tags->getNames();
        if (!self::validateTags($tags)) {
            throw new UnknownError("Empty tag is not valid. Please provide non-empty tags to flush.");
        }
        foreach ($tags as $tag) {
            $keys = $this->store->setFetch($tag);
            if (!$keys) {
                return false;
            } else {
                foreach ($keys as $key) {
                    $forgetResult = $this->store->forget($key);
                    if (!$forgetResult) {
                        return false;
                    } else {
                        $setRemoveElementResult = $this->store->setRemoveElement($tag, $key);
                        if (!$setRemoveElementResult) {
                            return false;
                        }
                    }
                }
            }
        }
        return true;
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
