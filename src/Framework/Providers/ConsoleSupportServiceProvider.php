<?php

declare(strict_types=1);

namespace FondBot\Framework\Providers;

use Illuminate\Database\MigrationServiceProvider;
use Illuminate\Foundation\Providers\ComposerServiceProvider;
use Illuminate\Foundation\Providers\ConsoleSupportServiceProvider as BaseConsoleSupportServiceProvider;

class ConsoleSupportServiceProvider extends BaseConsoleSupportServiceProvider
{
    protected $providers = [
        ArtisanServiceProvider::class,
        MigrationServiceProvider::class,
        ComposerServiceProvider::class,
    ];
}
