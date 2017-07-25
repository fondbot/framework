<?php

declare(strict_types=1);

namespace FondBot\Foundation;

use FondBot\Providers\DriverServiceProvider;
use Illuminate\Contracts\Foundation\Application;

class Factory
{
    public static function create(Application $application): Kernel
    {
        // Load service providers
        $application->register(DriverServiceProvider::class);

        // Boot kernel
        $kernel = Kernel::createInstance($application);

        $application->singleton(Kernel::class, $kernel);

        return $kernel;
    }
}
