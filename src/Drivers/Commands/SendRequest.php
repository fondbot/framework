<?php

declare(strict_types=1);

namespace FondBot\Drivers\Commands;

use FondBot\Drivers\Chat;
use FondBot\Drivers\User;
use FondBot\Drivers\Command;

class SendRequest implements Command
{
    private $chat;
    private $recipient;
    private $endpoint;
    private $parameters;

    public function __construct(Chat $chat, User $recipient, string $endpoint, array $parameters = [])
    {
        $this->chat = $chat;
        $this->recipient = $recipient;
        $this->endpoint = $endpoint;
        $this->parameters = $parameters;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName(): string
    {
        return 'SendRequest';
    }

    public function getChat(): Chat
    {
        return $this->chat;
    }

    public function getRecipient(): User
    {
        return $this->recipient;
    }

    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }
}
