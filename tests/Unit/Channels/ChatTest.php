<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Channels;

use FondBot\Channels\Chat;
use FondBot\Tests\TestCase;

class ChatTest extends TestCase
{
    public function testFull(): void
    {
        $user = Chat::make(
            $id = $this->faker()->uuid,
            $title = $this->faker()->title,
            $type = collect(['private', 'group'])->random()
        );

        $this->assertSame($id, $user->getId());
        $this->assertSame($title, $user->getTitle());
        $this->assertSame($type, $user->getType());
    }
}
