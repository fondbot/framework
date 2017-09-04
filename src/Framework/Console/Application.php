<?php

declare(strict_types=1);

namespace FondBot\Framework\Console;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Container\Container;
use Illuminate\Console\Application as BaseApplication;

class Application extends BaseApplication
{
    public function __construct(Container $laravel, Dispatcher $events, $version)
    {
        parent::__construct($laravel, $events, $version);

        $this->setName('FondBot Framework');
    }
}
