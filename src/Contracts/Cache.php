<?php

declare(strict_types=1);

namespace FondBot\Contracts;

interface Cache
{
    /**
     * Retrieve an item from the cache by key.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function get(string $key, $default = null);

    /**
     * Store an item in the cache.
     *
     * @param string $key
     * @param mixed  $value
     */
    public function store(string $key, $value): void;

    /**
     * Remove an item from the cache.
     *
     * @param string $key
     */
    public function forget(string $key): void;
}
