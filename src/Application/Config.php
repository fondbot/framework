<?php

declare(strict_types=1);

namespace FondBot\Application;

use JsonSerializable;
use FondBot\Helpers\Arr;

class Config implements JsonSerializable
{
    private $items;

    public function __construct(array $items)
    {
        $this->items = $items;
    }

    /**
     * Get option.
     *
     * @param string $key
     * @param null   $default
     *
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        return Arr::get($this->items, $key, $default);
    }

    /**
     * Set option.
     *
     * @param string $key
     * @param mixed  $value
     */
    public function set(string $key, $value): void
    {
        Arr::set($this->items, $key, $value);
    }

    /**
     * Determine if option exists.
     *
     * @param string $key
     *
     * @return bool
     */
    public function has(string $key): bool
    {
        return Arr::has($this->items, [$key]);
    }

    /**
     * Specify data which should be serialized to JSON.
     * @link  http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize(): array
    {
        return $this->items;
    }
}
