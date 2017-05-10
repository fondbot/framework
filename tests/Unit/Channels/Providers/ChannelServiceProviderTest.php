<?php

declare(strict_types=1);

namespace Tests\Unit\Channels\Providers;

use FondBot\Tests\TestCase;
use FondBot\Application\Config;
use FondBot\Channels\ChannelManager;
use FondBot\Channels\Providers\ChannelServiceProvider;

class ChannelServiceProviderTest extends TestCase
{
    public function test(): void
    {
        $this->container->add(Config::class, function () {
            $config = new Config();

            $config->set('channels', ['channel-1' => ['driver' => 'telegram', 'foo' => 'bar']]);

            return $config;
        });

        $this->container->addServiceProvider(new ChannelServiceProvider());

        /** @var ChannelManager $manager */
        $manager = $this->container->get(ChannelManager::class);

        $this->assertInstanceOf(ChannelManager::class, $manager);

        $channel = $manager->create('channel-1');

        $this->assertSame('channel-1', $channel->getName());
        $this->assertSame('telegram', $channel->getDriver());
        $this->assertSame(['foo' => 'bar'], $channel->getParameters());
    }
}
