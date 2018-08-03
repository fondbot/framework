<?php

declare(strict_types=1);

namespace FondBot\Channels;

use Illuminate\Support\Collection;

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
        $this->data = collect($data);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * Additional user information.
     *
     * @return Collection
     */
    public function getData(): Collection
    {
        return $this->data;
    }
}
