<?php

declare(strict_types=1);

namespace FondBot\Contracts;

interface LoggableArray
{

    /**
     * Return information for log.
     *
     * @return array
     */
    public function toLoggableArray(): array;
}
