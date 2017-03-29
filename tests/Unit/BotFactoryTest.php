<?php

declare(strict_types=1);

namespace Tests\Unit;

use FondBot\Bot;
use Tests\TestCase;
use FondBot\BotFactory;
use FondBot\Helpers\Str;
use FondBot\Channels\Channel;
use FondBot\Channels\DriverManager;
use FondBot\Contracts\Channels\Driver;

/**
 * @property mixed|\Mockery\Mock channel
 * @property mixed|\Mockery\Mock driverManager
 * @property mixed|\Mockery\Mock driver
 * @property BotFactory          factory
 */
class BotFactoryTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->channel = $this->mock(Channel::class);
        $this->driverManager = $this->mock(DriverManager::class);
        $this->driver = $this->mock(Driver::class);

        $this->factory = new BotFactory();
    }

    public function test_create()
    {
        $parameters = ['token' => Str::random()];

        $this->driverManager->shouldReceive('get')->with($this->channel)->andReturn($this->driver)->once();
        $this->channel->shouldReceive('getParameters')->andReturn($parameters)->once();
        $this->driver->shouldReceive('fill')->with($parameters, [], []);

        $bot = $this->factory->create($this->container, $this->channel, [], []);
        $this->assertInstanceOf(Bot::class, $bot);
    }
}
