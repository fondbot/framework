<?php
declare(strict_types=1);

namespace FondBot\Channels;

class Manager
{

    private $drivers = [
        'Telegram' => \FondBot\Channels\Drivers\Telegram::class,
    ];

    public function supportedDrivers(): array
    {
        return $this->drivers;
    }

}