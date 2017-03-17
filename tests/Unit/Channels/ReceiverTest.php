<?php

declare(strict_types=1);

namespace Tests\Unit\Channels;

use Tests\TestCase;
use FondBot\Channels\Receiver;

class ReceiverTest extends TestCase
{
    public function test_create()
    {
        $id = $this->faker()->uuid;
        $name = $this->faker()->name;
        $username = $this->faker()->userName;

        $receiver = Receiver::create($id, $name, $username);

        $this->assertEquals($id, $receiver->getIdentifier());
        $this->assertEquals($name, $receiver->getName());
        $this->assertEquals($username, $receiver->getUsername());
    }

    public function test_create_accepts_null_for_name_and_username()
    {
        $id = $this->faker()->uuid;

        $receiver = Receiver::create($id);

        $this->assertEquals($id, $receiver->getIdentifier());
        $this->assertNull($receiver->getName());
        $this->assertNull($receiver->getUsername());
    }
}
