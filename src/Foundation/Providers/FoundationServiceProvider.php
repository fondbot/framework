<?php

declare(strict_types=1);

namespace FondBot\Foundation\Providers;

use FondBot\Foundation\Kernel;
use FondBot\Foundation\ServiceProvider;

class FoundationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(Kernel::class, function () {
            return new Kernel;
        });
    }
}
