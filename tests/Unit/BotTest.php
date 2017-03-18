<?php

declare(strict_types=1);

namespace Tests\Unit;

use Bus;
use FondBot\Bot;
use Tests\TestCase;
use Illuminate\Http\Request;
use FondBot\Jobs\StartConversation;
use FondBot\Channels\ChannelManager;
use FondBot\Contracts\Channels\Driver;
use FondBot\Contracts\Database\Entities\Channel;

class BotTest extends TestCase
{
    public function test()
    {
        Bus::fake();

        $channelManager = $this->mock(ChannelManager::class);
        $channel = new Channel();
        $request = new Request();
        $driver = $this->mock(Driver::class);

        $channelManager->shouldReceive('createDriver')->with([], [], $channel)->andReturn($driver)->once();
        $driver->shouldReceive('verifyRequest')->once();

        $bot = new Bot($channelManager);
        $bot->setRequest($request);
        $bot->setChannel($channel);
        $bot->process();

        Bus::assertDispatched(StartConversation::class);
    }
}
