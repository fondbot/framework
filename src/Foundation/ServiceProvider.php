<?php

declare(strict_types=1);

namespace FondBot\Foundation;

use FondBot\Toolbelt\ToolbeltServiceProvider;
use Illuminate\Support\AggregateServiceProvider;
use FondBot\Foundation\Providers\RouteServiceProvider;
use FondBot\Foundation\Providers\FoundationServiceProvider;
use FondBot\Framework\Providers\ConsoleSupportServiceProvider;

class ServiceProvider extends AggregateServiceProvider
{
    protected $providers = [
        ConsoleSupportServiceProvider::class,
        FoundationServiceProvider::class,
        ToolbeltServiceProvider::class,
        RouteServiceProvider::class,
    ];
}
