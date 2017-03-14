<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Application;
use Illuminate\Contracts\Console\Kernel;

trait CreatesApplication
{
    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        /** @var Application $app */
        $app = require __DIR__.'/../vendor/laravel/laravel/bootstrap/app.php';
        $app->register(\FondBot\Providers\ServiceProvider::class);
        $app->make(Kernel::class)->bootstrap();

        return $app;
    }
}