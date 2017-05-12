<?php

declare(strict_types=1);

namespace FondBot\Cache;

use JsonSerializable;
use FondBot\Contracts\Cache;
use League\Flysystem\Filesystem;
use League\Flysystem\FileNotFoundException;

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
        try {
            $contents = $this->filesystem->read($this->key($key));
            $json = json_decode($contents, true);

            if (json_last_error() === JSON_ERROR_NONE) {
                return $json;
            }

            return $contents;
        } catch (FileNotFoundException $exception) {
            return $default;
        }
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

        $this->filesystem->put($this->key($key), $value);
    }

    /**
     * Remove an item from the cache.
     *
     * @param string $key
     */
    public function forget(string $key): void
    {
        try {
            $this->filesystem->delete($this->key($key));
        } catch (FileNotFoundException $exception) {
        }
    }

    private function key(string $key): string
    {
        return md5($key);
    }
}
