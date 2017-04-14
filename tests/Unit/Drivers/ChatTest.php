<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Drivers;

use FondBot\Drivers\Chat;
use FondBot\Tests\TestCase;

class ChatTest extends TestCase
{
    public function test_full()
    {
        $user = new Chat(
            $id = $this->faker()->uuid,
            $title = $this->faker()->title,
            $type = collect(['private', 'group'])->random()
        );

        $this->assertSame($id, $user->getId());
        $this->assertSame($title, $user->getTitle());
        $this->assertSame($type, $user->getType());
    }
}
