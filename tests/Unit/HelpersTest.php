<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit;

use FondBot\Tests\TestCase;
use FondBot\Conversation\Session;

class HelpersTest extends TestCase
{
    public function testKernel(): void
    {
        $this->assertSame($this->kernel, kernel());
    }

    public function testSession(): void
    {
        $session = $this->mock(Session::class);
        $this->setSession($session);

        $this->assertSame($session, session());
    }
}
