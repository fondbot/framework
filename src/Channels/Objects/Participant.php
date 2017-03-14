<?php
declare(strict_types=1);

namespace FondBot\Channels\Objects;

class Participant
{

    /** @var string */
    private $identifier;

    /** @var string */
    private $name;

    /** @var string */
    private $username;

    public static function create(string $identifier, string $name, string $username): Participant
    {
        $instance = new static;
        $instance->setIdentifier($identifier);
        $instance->setName($name);
        $instance->setUsername($username);

        return $instance;
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function setIdentifier($id): void
    {
        $this->identifier = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName($name): void
    {
        $this->name = $name;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername($username): void
    {
        $this->username = $username;
    }
}
