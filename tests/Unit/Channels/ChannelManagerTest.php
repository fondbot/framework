<?php
declare(strict_types=1);

namespace Tests\Unit\Channels;

use FondBot\Channels\ChannelManager;
use FondBot\Channels\Drivers\Telegram;
use FondBot\Database\Entities\Channel;
use Tests\TestCase;

/**
 * @property ChannelManager manager
 */
class ChannelManagerTest extends TestCase
{

    protected function setUp()
    {
        parent::setUp();

        $this->manager = new ChannelManager;
    }

    public function test_driver()
    {
        $channel = new Channel([
            'driver' => Telegram::class,
            'name' => $this->faker()->name,
            'parameters' => ['token' => str_random()],
        ]);

        $driver = $this->manager->createDriver(request(), $channel, true);

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