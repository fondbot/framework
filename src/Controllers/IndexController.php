<?php

declare(strict_types=1);

namespace FondBot\Controllers;

use FondBot\Foundation\Kernel;

class IndexController
{
    public function show(): string
    {
        return 'FondBot v'.Kernel::VERSION;
    }
}
