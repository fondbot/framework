<?php

declare(strict_types=1);

namespace FondBot\Cache;

use JsonSerializable;
use FondBot\Contracts\Cache;
use League\Flysystem\Filesystem;

class FilesystemCache implements Cache
{
    private $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
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
        return $this->filesystem->get($this->key($key)) ?? $default;
    }

    /**
     * Store an item in the cache.
     *
     * @param string $key
     * @param mixed  $value
     */
    public function store(string $key, $value): void
    {
        if (is_array($value)) {
            $value = json_encode($value);
        }
        if ($value instanceof JsonSerializable) {
            $value = json_encode($value->jsonSerialize());
        }

        $this->filesystem->put($this->key($key), $value);
    }

    /**
     * Remove an item from the cache.
     *
     * @param string $key
     */
    public function forget(string $key): void
    {
        $this->filesystem->delete($this->key($key));
    }

    private function key(string $key): string
    {
        return md5($key);
    }
}
