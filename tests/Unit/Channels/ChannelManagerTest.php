<?php

declare(strict_types=1);

namespace Tests\Unit\Channels;

use Tests\TestCase;
use FondBot\Channels\ChannelManager;
use FondBot\Channels\Telegram\TelegramDriver;
use FondBot\Contracts\Database\Entities\Channel;

/**
 * @property ChannelManager manager
 */
class ChannelManagerTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->manager = new ChannelManager([
            'Telegram' => TelegramDriver::class,
        ]);
    }

    public function test_createDriver()
    {
        $channel = new Channel([
            'driver' => TelegramDriver::class,
            'name' => $this->faker()->name,
            'parameters' => ['token' => str_random()],
        ]);

        $driver = $this->manager->createDriver([], [], $channel);

        $this->assertInstanceOf(TelegramDriver::class, $driver);
    }

    public function test_supportedDrivers()
    {
        $expected = [
            'Telegram' => TelegramDriver::class,
        ];

        $this->assertEquals($expected, $this->manager->supportedDrivers());
    }
}
