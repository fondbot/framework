<?php

declare(strict_types=1);

namespace FondBot\Drivers;

class User
{
    private $id;
    private $name;
    private $username;
    private $data;

    public function __construct(string $id, string $name = null, string $username = null, array $data = [])
    {
        $this->id = $id;
        $this->name = $name;
        $this->username = $username;
        $this->data = $data;
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

    /**
     * Additional user information.
     *
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }
}
