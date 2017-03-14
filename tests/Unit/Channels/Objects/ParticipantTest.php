<?php
declare(strict_types=1);

namespace Tests\Unit\Channels\Objects;

use FondBot\Channels\Objects\Participant;
use Tests\TestCase;

class ParticipantTest extends TestCase
{
    public function test_create()
    {
        $id = $this->faker()->uuid;
        $name = $this->faker()->name;
        $username = $this->faker()->userName;

        $participant = Participant::create($id, $name, $username);

        $this->assertEquals($id, $participant->getIdentifier());
        $this->assertEquals($name, $participant->getName());
        $this->assertEquals($username, $participant->getUsername());
    }
}
