<?php

declare(strict_types=1);

namespace FondBot\Channels\Exceptions;

use Exception;

class DriverException extends Exception
{
    public function report(): void
    {
        logger('DriverException', ['message' => $this->getMessage()]);
    }

    public function render()
    {
        return response();
    }
}
