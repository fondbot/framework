<?php

declare(strict_types=1);

namespace Tests\Classes\Fakes;

use Faker\Factory;
use FondBot\Contracts\Database\Entities\Channel;
use FondBot\Conversation\Context;
use FondBot\Conversation\Interaction;
use FondBot\Conversation\Story;

class FakeContext extends Context
{

    public static function create(Story $story = null, Interaction $interaction = null)
    {
        $faker = Factory::create();

        /** @var Channel $channel */
        $channel = Channel::firstOrCreate([
            'driver' => FakeDriver::class,
            'name' => $faker->word,
            'parameters' => [],
        ]);

        return new Context(
            $channel,
            new FakeSender(),
            FakeSenderMessage::create(),
            $story,
            $interaction,
            []
        );
    }

}
