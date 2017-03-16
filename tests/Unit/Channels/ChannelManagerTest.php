<?php

declare(strict_types=1);

namespace Tests\Unit\Channels;

use Tests\TestCase;
use FondBot\Channels\ChannelManager;
use FondBot\Channels\Drivers\Telegram;
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
            'Telegram' => Telegram::class,
        ]);
    }

    public function test_createDriver()
    {
        $channel = new Channel([
            'driver' => Telegram::class,
            'name' => $this->faker()->name,
            'parameters' => ['token' => str_random()],
        ]);

        $driver = $this->manager->createDriver([], $channel);

        $this->assertInstanceOf(Telegram::class, $driver);
    }

    public function test_supportedDrivers()
    {
        $expected = [
            'Telegram' => Telegram::class,
        ];

        $this->assertEquals($expected, $this->manager->supportedDrivers());
    }
}
