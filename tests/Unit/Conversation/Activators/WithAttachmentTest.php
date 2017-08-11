<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Conversation\Activators;

use FondBot\Tests\TestCase;
use FondBot\Templates\Attachment;
use FondBot\Events\MessageReceived;
use FondBot\Conversation\Activators\WithAttachment;

class WithAttachmentTest extends TestCase
{
    public function testMatchesWithoutType(): void
    {
        $message = new MessageReceived('/start', null, new Attachment());

        $activator = new WithAttachment;

        $this->assertTrue($activator->matches($message));
    }

    public function testDoesNotMatchWithoutType(): void
    {
        $message = new MessageReceived('/start', null, null);

        $activator = new WithAttachment;

        $this->assertFalse($activator->matches($message));
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

        $message = new MessageReceived('/start', null, $attachment);

        $this->assertTrue($activator->matches($message));
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

        $message = new MessageReceived('/start', null, $attachment);

        $this->assertFalse($activator->matches($message));
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
