<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Channels;

use FondBot\Tests\TestCase;
use FondBot\Channels\Channel;
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

        $manager = new ChannelManager();

        $manager->add($name, $parameters);

        $result = $manager->create($name);

        $this->assertInstanceOf(Channel::class, $result);
        $this->assertSame($name, $result->getName());
        $this->assertSame(collect($parameters)->except('driver')->toArray(), $result->getParameters());
        $this->assertSame($parameters['token'], $result->getParameter('token'));
    }

    public function testAll(): void
    {
        $manager = new ChannelManager();
        $manager->add('foo', ['foo' => 'bar']);

        $this->assertSame(['foo' => ['foo' => 'bar']], $manager->all());
    }

    /**
     * @expectedException \FondBot\Channels\Exceptions\ChannelNotFound
     * @expectedExceptionMessage Channel `fake` not found.
     */
    public function testCreateException(): void
    {
        $manager = new ChannelManager();

        $manager->create('fake');
    }
}
