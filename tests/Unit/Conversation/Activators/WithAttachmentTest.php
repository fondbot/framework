<?php

declare(strict_types=1);

namespace Tests\Unit\Conversation\Activators;

use Tests\TestCase;
use FondBot\Drivers\Message\Attachment;
use Tests\Classes\Fakes\FakeReceivedMessage;
use FondBot\Conversation\Activators\WithAttachment;

class WithAttachmentTest extends TestCase
{
    public function test_matches_without_type()
    {
        $activator = new WithAttachment;
        $attachment = new Attachment($this->faker()->word, $this->faker()->imageUrl());

        $this->assertTrue(
            $activator->matches(new FakeReceivedMessage(null, null, null, $attachment))
        );
    }

    public function test_does_not_match_without_type()
    {
        $activator = new WithAttachment;

        $this->assertFalse(
            $activator->matches(new FakeReceivedMessage)
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

        $this->assertTrue(
            $activator->matches(new FakeReceivedMessage(null, null, null, $attachment))
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

        $this->assertFalse(
            $activator->matches(new FakeReceivedMessage(null, null, null, $attachment))
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
