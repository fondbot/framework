<?php

declare(strict_types=1);

namespace Tests\Unit\Conversation;

use FondBot\Bot;
use Tests\TestCase;
use FondBot\Conversation\Intent;

class IntentTest extends TestCase
{
    public function test_handle()
    {
        $bot = $this->mock(Bot::class);

        /** @var Intent $intent */
        $intent = $this->mock(Intent::class)->shouldIgnoreMissing();
        $intent->handle($bot);
    }
}
