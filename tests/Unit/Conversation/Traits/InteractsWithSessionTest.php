<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Conversation\Traits;

use FondBot\Drivers\Chat;
use FondBot\Drivers\User;
use FondBot\Tests\TestCase;
use FondBot\Conversation\Session;
use FondBot\Conversation\Traits\InteractsWithSession;

class InteractsWithSessionTest extends TestCase
{
    use InteractsWithSession;

    public function testChat(): void
    {
        $session = $this->mock(Session::class);
        $chat = $this->mock(Chat::class);

        $this->setSession($session);

        $session->shouldReceive('getChat')->andReturn($chat)->once();

        $this->assertSame($chat, $this->getChat());
    }

    public function testUser(): void
    {
        $session = $this->mock(Session::class);
        $user = $this->mock(User::class);

        $this->setSession($session);

        $session->shouldReceive('getUser')->andReturn($user)->once();

        $this->assertSame($user, $this->getUser());
    }
}
