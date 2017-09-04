<?php

declare(strict_types=1);

namespace FondBot\Framework\Console;

use Illuminate\Foundation\Console\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    /** {@inheritdoc} */
    protected function getArtisan()
    {
        if ($this->artisan === null) {
            return $this->artisan = (new Application($this->app, $this->events, $this->app->version()))
                ->resolveCommands($this->commands);
        }

        return $this->artisan;
    }
}
