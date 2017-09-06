<?php

declare(strict_types=1);

namespace FondBot\Foundation\Providers;

use FondBot\Foundation\Kernel;
use FondBot\Foundation\ServiceProvider;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Contracts\Container\Container;

class FoundationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(Kernel::class, function () {
            return new Kernel(resolve(Container::class), resolve(Dispatcher::class));
        });
    }
}
