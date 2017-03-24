<?php

declare(strict_types=1);

namespace Tests\Unit\Conversation\Fallback;

use FondBot\Contracts\Database\Entities\Channel;
use Tests\Classes\Fakes\FakeSender;
use Tests\Classes\Fakes\FakeSenderMessage;
use Tests\TestCase;
use FondBot\Conversation\Context;
use FondBot\Conversation\Fallback\FallbackStory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use FondBot\Conversation\Fallback\FallbackInteraction;

/**
 * @property FallbackInteraction interaction
 */
class FallbackInteractionTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp()
    {
        parent::setUp();

        $this->interaction = new FallbackInteraction;
    }

    public function test_process()
    {
        $context = new Context(
            new Channel(),
            new FakeSender(),
            FakeSenderMessage::create(),
            new FallbackStory,
            $this->interaction
        );

        $this->interaction->setContext($context);
        $this->interaction->run();
    }
}
