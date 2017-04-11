<?php

declare(strict_types=1);

namespace Tests\Unit\Conversation;

use FondBot\Kernel;
use Tests\TestCase;
use FondBot\Conversation\Intent;

class IntentTest extends TestCase
{
    public function test_handle()
    {
        $kernel = $this->mock(Kernel::class);

        /** @var Intent $intent */
        $intent = $this->mock(Intent::class)->shouldIgnoreMissing();
        $intent->handle($kernel);
    }
}
