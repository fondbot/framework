<?php

declare(strict_types=1);

namespace FondBot\Controllers;

use FondBot\Foundation\Kernel;

class Controller
{
    public function run(): string
    {
        return 'FondBot v'.Kernel::VERSION;
    }
}
