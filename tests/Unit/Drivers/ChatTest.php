<?php

declare(strict_types=1);

namespace Tests\Unit\Drivers;

use Tests\TestCase;
use FondBot\Drivers\Chat;

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
