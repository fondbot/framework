<?php

declare(strict_types=1);

namespace FondBot\Drivers;

class User
{
    private $id;
    private $name;
    private $username;

    public function __construct(string $id, string $name = null, string $username = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->username = $username;
    }

    /**
     * Identifier.
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Full name.
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Username.
     *
     * @return string|null
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }
}
