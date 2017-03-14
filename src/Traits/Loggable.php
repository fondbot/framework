<?php declare(strict_types=1);

namespace FondBot\Traits;

trait Loggable
{
    protected function debug(string $message, array $context = []): void
    {
        $class = get_class($this);
        logger($class . '.' . $message, $context);
    }

    protected function error(string $message, array $context = []): void
    {
        $class = get_class($this);
        logger()->error($class . '.' . $message, $context);
    }
}
