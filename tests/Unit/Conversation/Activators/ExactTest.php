<?php

declare(strict_types=1);

namespace Tests\Unit\Conversation\Activators;

use Tests\TestCase;
use FondBot\Conversation\Activators\Exact;
use Tests\Classes\Fakes\FakeReceivedMessage;

class ExactTest extends TestCase
{
    public function test_matches()
    {
        $activator = new Exact('/start');
        $this->assertTrue(
            $activator->matches(new FakeReceivedMessage(null, '/start'))
        );
    }

    public function test_does_not_match()
    {
        $activator = new Exact('/start');
        $this->assertFalse(
            $activator->matches(new FakeReceivedMessage(null, '/stop'))
        );
    }
}
