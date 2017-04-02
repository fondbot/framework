<?php

declare(strict_types=1);

namespace Tests\Unit\Conversation\Fallback;

use FondBot\Bot;
use Tests\TestCase;
use FondBot\Conversation\Context;
use FondBot\Contracts\Channels\User;
use FondBot\Conversation\Fallback\FallbackIntent;

/**
 * @property FallbackIntent $intent
 */
class FallbackIntentTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->intent = new FallbackIntent;
    }

    public function test_activators()
    {
        $this->assertSame([], $this->intent->activators());
    }

    public function test_run()
    {
        $bot = $this->mock(Bot::class);
        $context = $this->mock(Context::class);
        $user = $this->mock(User::class);

        $bot->shouldReceive('getContext')->andReturn($context)->atLeast()->once();
        $context->shouldReceive('getUser')->andReturn($user)->atLeast()->once();
        $bot->shouldReceive('sendMessage')->once();

        $this->intent->handle($bot);
    }
}
