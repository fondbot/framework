<?php

declare(strict_types=1);

namespace FondBot\Framework;

use FondBot\Foundation\Kernel;
use FondBot\Framework\Http\Kernel as HttpKernel;
use FondBot\Framework\Console\Kernel as ConsoleKernel;
use Illuminate\Foundation\Application as BaseApplication;
use Illuminate\Contracts\Http\Kernel as HttpKernelContract;
use FondBot\Framework\Exceptions\Handler as ExceptionsHandler;
use Illuminate\Contracts\Console\Kernel as ConsoleKernelContract;
use Illuminate\Contracts\Debug\ExceptionHandler as ExceptionHandlerContract;

class Application extends BaseApplication
{
    /** {@inheritdoc} */
    public function version(): string
    {
        return Kernel::VERSION;
    }

    /** {@inheritdoc} */
    public function configPath($path = ''): string
    {
        return $this->basePath('vendor/fondbot/framework/config');
    }

    /** {@inheritdoc} */
    protected function registerBaseBindings(): void
    {
        parent::registerBaseBindings();

        $this->singleton(HttpKernelContract::class, HttpKernel::class);
        $this->singleton(ConsoleKernelContract::class, ConsoleKernel::class);
        $this->singleton(ExceptionHandlerContract::class, ExceptionsHandler::class);
    }
}
