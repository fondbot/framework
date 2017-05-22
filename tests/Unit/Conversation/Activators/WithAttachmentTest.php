<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Conversation\Activators;

use FondBot\Tests\TestCase;
use FondBot\Templates\Attachment;
use FondBot\Drivers\ReceivedMessage;
use FondBot\Conversation\Activators\WithAttachment;

/**
 * @property mixed|\Mockery\Mock message
 */
class WithAttachmentTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->message = $this->mock(ReceivedMessage::class);
    }

    public function test_matches_without_type(): void
    {
        $activator = new WithAttachment;

        $this->message->shouldReceive('hasAttachment')->andReturn(true)->atLeast()->once();

        $this->assertTrue(
            $activator->matches($this->message)
        );
    }

    public function test_does_not_match_without_type(): void
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
    public function test_matches_with_type(string $type): void
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
    public function test_does_not_match_with_type(string $type): void
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
