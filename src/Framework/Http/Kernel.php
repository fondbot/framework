<?php

declare(strict_types=1);

namespace FondBot\Framework\Http;

use FondBot\Foundation\Middleware\InitializeKernel;
use Illuminate\Foundation\Http\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    protected $middlewareGroups = [
        'fondbot.webhook' => [
            InitializeKernel::class,
        ],
    ];
}
