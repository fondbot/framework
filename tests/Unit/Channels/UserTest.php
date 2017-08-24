<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Channels;

use FondBot\Channels\User;
use FondBot\Tests\TestCase;

class UserTest extends TestCase
{
    public function testFull()
    {
        $user = new User(
            $id = $this->faker()->uuid,
            $name = $this->faker()->name,
            $username = $this->faker()->userName,
            $data = ['foo' => 'bar']
        );

        $this->assertSame($id, $user->getId());
        $this->assertSame($name, $user->getName());
        $this->assertSame($username, $user->getUsername());
        $this->assertSame($data, $user->getData());
    }

    public function testAcceptsNullsForNameAndUsername()
    {
        $user = new User($id = $this->faker()->uuid, null, null);

        $this->assertSame($id, $user->getId());
        $this->assertNull($user->getName());
        $this->assertNull($user->getUsername());
    }
}
