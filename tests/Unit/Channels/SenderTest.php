<?php

declare(strict_types=1);

namespace Tests\Unit\Channels;

use Tests\TestCase;
use FondBot\Contracts\Channels\Sender;

class SenderTest extends TestCase
{
    public function test_create()
    {
        $id = $this->faker()->uuid;
        $name = $this->faker()->name;
        $username = $this->faker()->userName;

        $sender = Sender::create($id, $name, $username);

        $this->assertEquals($id, $sender->getIdentifier());
        $this->assertEquals($name, $sender->getName());
        $this->assertEquals($username, $sender->getUsername());
    }

    public function test_create_accepts_null_for_name_and_username()
    {
        $id = $this->faker()->uuid;

        $sender = Sender::create($id);

        $this->assertEquals($id, $sender->getIdentifier());
        $this->assertNull($sender->getName());
        $this->assertNull($sender->getUsername());
    }
}
