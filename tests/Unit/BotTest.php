<?php

declare(strict_types=1);

namespace Tests\Unit;

use Bus;
use FondBot\Bot;
use Tests\TestCase;
use Illuminate\Http\Request;
use Tests\Classes\Fakes\FakeDriver;
use FondBot\Contracts\Database\Entities\Channel;
use FondBot\Conversation\Jobs\StartConversation;

/**
 * @property Channel channel
 */
class BotTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->channel = new Channel([
            'driver' => FakeDriver::class,
            'parameters' => [],
        ]);
    }

    public function test_request()
    {
        Bus::fake();

        $request = new Request();

        /** @var Bot $bot */
        $bot = resolve(Bot::class);
        $bot->setRequest($request);
        $bot->setChannel($this->channel);
        $bot->process();

        Bus::assertDispatched(StartConversation::class);
    }

    public function test_verification()
    {
        Bus::fake();

        $request = new Request([], [], [], [], [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['verification' => $this->faker()->uuid])
        );

        $bot = resolve(Bot::class);
        $bot->setRequest($request);
        $bot->setChannel($this->channel);
        $result = $bot->process();

        $this->assertSame($request->json('verification'), $result);

        Bus::assertNotDispatched(StartConversation::class);
    }
}
