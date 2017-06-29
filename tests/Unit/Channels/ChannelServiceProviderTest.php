<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Channels;

use FondBot\Tests\TestCase;
use FondBot\Channels\ChannelManager;
use FondBot\Channels\ChannelServiceProvider;

class ChannelServiceProviderTest extends TestCase
{
    public function test(): void
    {
        $provider = $this->mock(ChannelServiceProvider::class)->makePartial();
        $provider->shouldReceive('channels')->andReturn(['channel-1' => ['driver' => 'telegram', 'foo' => 'bar']])->once();

        $this->container->addServiceProvider($provider);

        /** @var ChannelManager $manager */
        $manager = $this->container->get(ChannelManager::class);

        $this->assertInstanceOf(ChannelManager::class, $manager);

        $channel = $manager->create('channel-1');

        $this->assertSame('channel-1', $channel->getName());
        $this->assertSame('telegram', $channel->getDriver());
        $this->assertSame(['foo' => 'bar'], $channel->getParameters());
    }
}
