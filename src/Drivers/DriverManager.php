<?php

declare(strict_types=1);

namespace FondBot\Drivers;

use Illuminate\Support\Manager;

class DriverManager extends Manager
{
    /**
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultDriver(): ?string
    {
        return null;
    }
}
