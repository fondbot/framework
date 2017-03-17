<?php

declare(strict_types=1);

namespace FondBot\Channels;

class Sender
{
    /** @var string */
    private $identifier;

    /** @var string|null */
    private $name;

    /** @var string|null */
    private $username;

    public static function create(string $identifier, string $name = null, string $username = null): Sender
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

    public function setIdentifier(string $id): void
    {
        $this->identifier = $id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): void
    {
        $this->username = $username;
    }
}
