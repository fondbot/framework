<?php

declare(strict_types=1);

namespace Tests\Classes\Fakes;

use Faker\Generator;
use FondBot\Contracts\Channels\User;

class FakeUser implements User
{
    private $faker;

    public function __construct(Generator $faker)
    {
        $this->faker = $faker;
    }

    /**
     * Identifier.
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->faker->uuid;
    }

    /**
     * Full name.
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->faker->name;
    }

    /**
     * Username.
     *
     * @return string|null
     */
    public function getUsername(): ?string
    {
        return $this->faker->userName;
    }
}
