<?php

declare(strict_types=1);

namespace Tests\Unit\Channels;

use FondBot\Channels\Receiver;
use Tests\TestCase;

class ReceiverTest extends TestCase
{
    public function test_create()
    {
        $id = $this->faker()->uuid;
        $name = $this->faker()->name;
        $username = $this->faker()->userName;

        $participant = Receiver::create($id, $name, $username);

        $this->assertEquals($id, $participant->getIdentifier());
        $this->assertEquals($name, $participant->getName());
        $this->assertEquals($username, $participant->getUsername());
    }
}
