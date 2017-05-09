<?php

declare(strict_types=1);

namespace FondBot\Application;

use FondBot\Helpers\Arr;

class Config
{
    private $values = [];

    public function get(string $key, $default = null)
    {
        return Arr::get($this->values, $key, $default);
    }

    public function set(string $key, $value): void
    {
        Arr::set($this->values, $key, $value);
    }
}
