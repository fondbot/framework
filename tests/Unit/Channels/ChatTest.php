<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Channels;

use FondBot\Channels\Chat;
use FondBot\Tests\TestCase;

class ChatTest extends TestCase
{
    public function testFull(): void
    {
        $chat = new Chat($id = $this->faker()->uuid, $title = $this->faker()->title, 'foo');

        $this->assertSame($id, $chat->getId());
        $this->assertSame($title, $chat->getTitle());
        $this->assertSame('foo', $chat->getType());
    }
}
