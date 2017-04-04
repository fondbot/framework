<?php

declare(strict_types=1);

namespace Tests\Unit\Conversation\Activators;

use FondBot\Contracts\Drivers\Message\Attachment;
use FondBot\Contracts\Drivers\ReceivedMessage;
use Tests\TestCase;
use FondBot\Conversation\Activators\WithAttachment;

/**
 * @property mixed|\Mockery\Mock message
 */
class WithAttachmentTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->message = $this->mock(ReceivedMessage::class);
    }

    public function test_matches_without_type()
    {
        $activator = new WithAttachment;

        $this->message->shouldReceive('hasAttachment')->andReturn(true)->atLeast()->once();

        $this->assertTrue(
            $activator->matches($this->message)
        );
    }

    public function test_does_not_match_without_type()
    {
        $activator = new WithAttachment;

        $this->message->shouldReceive('hasAttachment')->andReturn(false)->atLeast()->once();

        $this->assertFalse(
            $activator->matches($this->message)
        );
    }

    /**
     * @dataProvider types
     *
     * @param string $type
     */
    public function test_matches_with_type(string $type)
    {
        $activator = new WithAttachment($type);
        $attachment = new Attachment($type, $this->faker()->imageUrl());

        $this->message->shouldReceive('hasAttachment')->andReturn(true)->atLeast()->once();
        $this->message->shouldReceive('getAttachment')->andReturn($attachment)->atLeast()->once();

        $this->assertTrue(
            $activator->matches($this->message)
        );
    }

    /**
     * @dataProvider types
     *
     * @param string $type
     */
    public function test_does_not_match_with_type(string $type)
    {
        $activator = new WithAttachment($type);
        $otherType = collect(Attachment::possibleTypes())
            ->filter(function ($item) use ($type) {
                return $item !== $type;
            })
            ->random();

        $attachment = new Attachment($otherType, $this->faker()->imageUrl());

        $this->message->shouldReceive('hasAttachment')->andReturn(true)->atLeast()->once();
        $this->message->shouldReceive('getAttachment')->andReturn($attachment)->atLeast()->once();

        $this->assertFalse(
            $activator->matches($this->message)
        );
    }

    public function types(): array
    {
        return collect(Attachment::possibleTypes())
            ->map(function ($item) {
                return [$item];
            })
            ->toArray();
    }
}
