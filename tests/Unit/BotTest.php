<?php

declare(strict_types=1);

namespace Tests\Unit;

use Bus;
use FondBot\Bot;
use Tests\TestCase;
use Illuminate\Http\Request;
use Tests\Classes\FakeDriver;
use FondBot\Jobs\StartConversation;
use FondBot\Contracts\Database\Entities\Channel;

class BotTest extends TestCase
{
    public function test_request()
    {
        Bus::fake();

        $channel = new Channel(['driver' => FakeDriver::class]);
        $request = new Request();

        $bot = resolve(Bot::class);
        $bot->setRequest($request);
        $bot->setChannel($channel);
        $bot->process();

        Bus::assertDispatched(StartConversation::class);
    }

    public function test_verification()
    {
        Bus::fake();

        $channel = new Channel(['driver' => FakeDriver::class]);
        $request = new Request([], [], [], [], [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['verification' => $this->faker()->uuid])
        );

        $bot = resolve(Bot::class);
        $bot->setRequest($request);
        $bot->setChannel($channel);
        $result = $bot->process();

        $this->assertSame($request->json('verification'), $result);

        Bus::assertNotDispatched(StartConversation::class);
    }
}
