<?php

declare(strict_types=1);

namespace FondBot\Contracts\Channels;

class Receiver
{
    private $identifier;
    private $name;
    private $username;

    public function __construct(string $identifier, string $name = null, string $username = null)
    {
        $this->identifier = $identifier;
        $this->name = $name;
        $this->username = $username;
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }
}
