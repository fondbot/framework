<?php
declare(strict_types=1);

namespace FondBot\Channels;

use FondBot\Channels\Abstracts\Driver;
use FondBot\Database\Entities\Channel;
use Illuminate\Http\Request;

class ChannelManager
{

    private $drivers = [
        'Telegram' => Drivers\Telegram::class,
    ];

    /**
     * Get driver instance
     *
     * @param Request $request
     * @param Channel $channel
     *
     * @param bool $initialise
     * @return Driver
     */
    public function driver(Request $request, Channel $channel, bool $initialise = true): Driver
    {
        /** @var Driver $driver */
        $driver = new $channel->driver($request, $channel->name, $channel->parameters);
        if ($initialise) {
            $driver->init();
        }

        return $driver;
    }

    /**
     * List of supported drivers
     *
     * @return array
     */
    public function supportedDrivers(): array
    {
        return $this->drivers;
    }

}