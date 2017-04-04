<?php

declare(strict_types=1);

namespace FondBot\Contracts\Drivers\Message;

interface Location
{
    /**
     * Get latitude.
     *
     * @return float
     */
    public function getLatitude(): float;

    /**
     * Get longitude.
     *
     * @return float
     */
    public function getLongitude(): float;
}
