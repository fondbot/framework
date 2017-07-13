<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Drivers;

use FondBot\Drivers\User;
use FondBot\Tests\TestCase;

class UserTest extends TestCase
{
    public function testFull()
    {
        $user = new User(
            $id = $this->faker()->uuid,
            $name = $this->faker()->name,
            $username = $this->faker()->userName
        );

        $this->assertSame($id, $user->getId());
        $this->assertSame($name, $user->getName());
        $this->assertSame($username, $user->getUsername());
    }

    public function testAcceptsNullsForNameAndUsername()
    {
        $user = new User($id = $this->faker()->uuid, null, null);

        $this->assertSame($id, $user->getId());
        $this->assertNull($user->getName());
        $this->assertNull($user->getUsername());
    }
}
