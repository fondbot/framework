<?php

declare(strict_types=1);

namespace Tests\Unit\Channels;

use Tests\TestCase;
use Tests\Classes\Fakes\FakeDriver;
use FondBot\Channels\ChannelManager;
use FondBot\Contracts\Database\Entities\Channel;

/**
 * @property ChannelManager manager
 */
class ChannelManagerTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->manager = new ChannelManager();
        $this->manager->add('fake', FakeDriver::class);
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
        $expected = ['fake' => FakeDriver::class];

        $this->assertEquals($expected, $this->manager->supportedDrivers());
    }
}
