<?php

declare(strict_types=1);

namespace FondBot\Channels\Telegram;

use FondBot\Contracts\Channels\User;

class TelegramUser implements User
{
    private $payload;

    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }

    /**
     * Identifier.
     *
     * @return string
     */
    public function getId(): string
    {
        return (string) $this->payload['id'];
    }

    /**
     * Full name.
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->payload['first_name'].' '.$this->payload['last_name'];
    }

    /**
     * Username.
     *
     * @return string|null
     */
    public function getUsername(): ?string
    {
        return $this->payload['username'];
    }
}
