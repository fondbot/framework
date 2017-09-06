<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Channels;

use FondBot\Tests\TestCase;
use FondBot\Channels\Channel;
use FondBot\Tests\Mocks\FakeDriver;
use FondBot\Channels\ChannelManager;

class ChannelManagerTest extends TestCase
{
    public function testCreate(): void
    {
        $name = 'fake';
        $parameters = [
            'driver' => 'fake',
            'token' => $this->faker()->sha1,
        ];
        $driver = new FakeDriver;

        $manager = new ChannelManager($this->app);
        $manager->extend('fake', function () use (&$driver) {
            return $driver;
        });
        $manager->register([$name => $parameters]);

        $result = $manager->get($name);

        $this->assertInstanceOf(Channel::class, $result);
        $this->assertSame($name, $result->getName());
        $this->assertSame($driver, $result->getDriver());
    }

    public function testAll(): void
    {
        $manager = new ChannelManager($this->app);
        $manager->register(['foo' => ['foo' => 'bar']]);

        $this->assertSame(['foo' => ['foo' => 'bar']], $manager->all());
    }

    /**
     * @expectedException \FondBot\Channels\Exceptions\ChannelNotFound
     * @expectedExceptionMessage Channel `fake` not found.
     */
    public function testGetException(): void
    {
        $manager = new ChannelManager($this->app);

        $manager->get('fake');
    }

    public function testNoDefaultDriver(): void
    {
        $manager = new ChannelManager($this->app);

        $this->assertNull($manager->getDefaultDriver());
    }
}
