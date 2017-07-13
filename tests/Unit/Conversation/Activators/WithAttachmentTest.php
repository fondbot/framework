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

    public function testMatchesWithoutType(): void
    {
        $activator = new WithAttachment;

        $this->message->shouldReceive('hasAttachment')->andReturn(true)->atLeast()->once();

        $this->assertTrue(
            $activator->matches($this->message)
        );
    }

    public function testDoesNotMatchWithoutType(): void
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
    public function testMatchesWithType(string $type): void
    {
        $activator = new WithAttachment($type);
        $attachment = (new Attachment)->setType($type);

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
    public function testDoesNotMatchWithType(string $type): void
    {
        $activator = new WithAttachment($type);
        $otherType = collect(Attachment::possibleTypes())
            ->filter(function ($item) use ($type) {
                return $item !== $type;
            })
            ->random();

        $attachment = (new Attachment)->setType($otherType);

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
