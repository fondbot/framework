<?php

declare(strict_types=1);

namespace Tests\Unit\Drivers;

use Tests\TestCase;
use FondBot\Drivers\User;

class UserTest extends TestCase
{
    public function test_full()
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

    public function test_accepts_nulls_for_name_and_username()
    {
        $user = new User($id = $this->faker()->uuid, null, null);

        $this->assertSame($id, $user->getId());
        $this->assertNull($user->getName());
        $this->assertNull($user->getUsername());
    }
}
