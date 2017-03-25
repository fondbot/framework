<?php

declare(strict_types=1);

namespace Tests\Unit\Channels;

use FondBot\Contracts\Database\Entities\Channel;
use Tests\Classes\Fakes\FakeDriver;
use Tests\TestCase;
use FondBot\Channels\ChannelManager;
use FondBot\Channels\Telegram\TelegramDriver;

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
        /** @var Channel $channel */
        $channel = $this->factory(Channel::class)->create();

        $driver = $this->manager->createDriver($channel, [], []);

        $this->assertInstanceOf(FakeDriver::class, $driver);
    }

    public function test_supportedDrivers()
    {
        $expected = [
            'Telegram' => TelegramDriver::class,
        ];

        $this->assertEquals($expected, $this->manager->supportedDrivers());
    }
}
