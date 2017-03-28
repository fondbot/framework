<?php

declare(strict_types=1);

namespace FondBot\Frameworks\Laravel\Cache;

use Illuminate\Contracts\Cache\Repository;
use FondBot\Contracts\Cache\Cache as CacheContract;

class Cache implements CacheContract
{
    private $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Retrieve an item from the cache by key.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        return $this->repository->get($key, $default);
    }

    /**
     * Store an item in the cache.
     *
     * @param string $key
     * @param mixed  $value
     */
    public function store(string $key, $value): void
    {
        $this->repository->forever($key, $value);
    }

    /**
     * Remove an item from the cache.
     *
     * @param string $key
     */
    public function forget(string $key): void
    {
        $this->repository->forget($key);
    }
}
