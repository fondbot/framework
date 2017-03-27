<?php

declare(strict_types=1);

namespace Tests\Unit\Channels;

use FondBot\Channels\Channel;
use FondBot\Channels\ChannelManager;
use Tests\TestCase;

class ChannelManagerTest extends TestCase
{

    public function test_create()
    {
        $name = 'fake';
        $parameters = [
            'driver' => 'fake',
            'token' => str_random(16),
        ];

        $manager = new ChannelManager();

        $manager->add($name, $parameters);

        $result = $manager->create($name);

        $this->assertInstanceOf(Channel::class, $result);
        $this->assertSame($name, $result->getName());
        $this->assertSame($parameters, $result->getParameters());
    }

    /**
     * @expectedException \FondBot\Channels\Exceptions\ChannelNotFoundException
     * @expectedExceptionMessage Channel `fake` not found.
     */
    public function test_create_exception()
    {
        $manager = new ChannelManager();

        $manager->create('fake');
    }

}
