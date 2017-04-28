<?php

declare(strict_types=1);

namespace FondBot\Channels;

class MiddlewareManager
{
    private $middlewares = [];

    public function add(Middleware $middleware): void
    {
        $this->middlewares = $middleware;
    }

    /**
     * @return Middleware[]
     */
    public function all(): array
    {
        return $this->middlewares;
    }
}
