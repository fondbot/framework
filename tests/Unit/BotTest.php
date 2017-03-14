<?php
declare(strict_types=1);

namespace Tests\Unit;

use Bus;
use FondBot\Bot;
use FondBot\Channels\ChannelManager;
use FondBot\Channels\Driver;
use FondBot\Database\Entities\Channel;
use FondBot\Jobs\StartConversation;
use Tests\TestCase;

class BotTest extends TestCase
{
    public function test()
    {
        Bus::fake();

        $channelManager = $this->mock(ChannelManager::class);
        $channel = $this->mock(Channel::class);
        $driver = $this->mock(Driver::class);

        $channelManager->shouldReceive('createDriver')->with([], $channel)->andReturn($driver)->once();
        $driver->shouldReceive('verifyRequest')->once();

        $bot = new Bot($channelManager);
        $bot->process($channel);

        Bus::assertDispatched(StartConversation::class);
    }
}
