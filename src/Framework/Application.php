<?php

declare(strict_types=1);

namespace FondBot\Framework;

use FondBot\Foundation\Kernel;
use Illuminate\Foundation\Application as BaseApplication;

class Application extends BaseApplication
{
    /** {@inheritdoc} */
    public function version(): string
    {
        return Kernel::VERSION;
    }
}
