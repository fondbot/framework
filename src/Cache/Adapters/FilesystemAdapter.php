<?php

declare(strict_types=1);

namespace FondBot\Cache\Adapters;

use JsonSerializable;
use Cache\Adapter\Filesystem\FilesystemCachePool;

/**
 * @deprecated Since 1.1 package cache/cache is used (https://packagist.org/packages/cache/cache)
 */
class FilesystemAdapter extends FilesystemCachePool
{
    public function get($key, $default = null)
    {
        $value = parent::get($key, $default);
        $json = json_decode($value, true);

        if (json_last_error() === JSON_ERROR_NONE) {
            return $json;
        }

        return $value;
    }

    /**
     * Store an item in the cache.
     *
     * @param string $key
     * @param mixed  $value
     */
    public function store(string $key, $value): void
    {
        if ($value instanceof JsonSerializable) {
            $value = json_encode($value->jsonSerialize());
        } else {
            $value = json_encode($value);
        }

        $this->set($key, $value);
    }

    /**
     * Remove an item from the cache.
     *
     * @param string $key
     */
    public function forget(string $key): void
    {
        $this->delete($key);
    }
}
