<?php

declare(strict_types=1);

namespace Tests\Unit\Conversation\Fallback;

use Tests\TestCase;
use FondBot\Contracts\Channels\ReceivedMessage;
use FondBot\Conversation\Fallback\FallbackInteraction;

/**
 * @property FallbackInteraction interaction
 */
class FallbackInteractionTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->interaction = new FallbackInteraction();
    }

    public function test_keyboard()
    {
        $this->assertNull($this->interaction->keyboard());
    }

    public function test_text()
    {
        $this->assertTrue(in_array($this->interaction->text(), [
            'Sorry, I could not understand you.',
            'Oops, I can\'t do that 😔',
            'My developer did not teach to do that.',
        ], true));
    }

    public function test_process()
    {
        $this->interaction->process($this->mock(ReceivedMessage::class));
    }
}
