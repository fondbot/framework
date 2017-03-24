<?php

declare(strict_types=1);

namespace Tests\Classes\Fakes;

use Faker\Factory;
use FondBot\Contracts\Channels\Sender;

class FakeSender extends Sender
{

    public function __construct()
    {
        $faker = Factory::create();
        $this->setIdentifier($faker->uuid);
        $this->setName($faker->name);
        $this->setUsername($faker->userName);
    }

}
