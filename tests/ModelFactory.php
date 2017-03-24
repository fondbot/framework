<?php

declare(strict_types=1);

namespace Tests;

use Faker\Factory;
use Faker\Generator;
use Tests\Classes\Fakes\FakeDriver;
use FondBot\Contracts\Database\Entities\Channel;
use FondBot\Contracts\Database\Entities\Participant;

class ModelFactory
{
    public static function channel(array $attributes = []): Channel
    {
        $channel = new Channel([
            'driver' => $attributes['driver'] ?? FakeDriver::class,
            'name' => $attributes['name'] ?? self::faker()->word,
            'parameters' => $attributes['parameters'] ?? ['token' => str_random()],
        ]);

        $channel->save();

        return $channel->fresh();
    }

    public static function participant(array $attributes = []): Participant
    {
        $participant = new Participant([
            'channel_id' => $attributes['channel_id'] ?? self::faker()->numberBetween(),
            'identifier' => $attributes['identifier'] ?? self::faker()->uuid,
            'name' => $attributes['name'] ?? self::faker()->name,
            'username' => $attributes['username'] ?? self::faker()->userName,
        ]);

        $participant->save();

        return $participant->fresh();
    }

    private static function faker(): Generator
    {
        return Factory::create();
    }
}
