<?php

declare(strict_types=1);

namespace FondBot\Drivers\Commands;

use FondBot\Drivers\Chat;
use FondBot\Drivers\User;
use FondBot\Drivers\Command;
use InvalidArgumentException;
use FondBot\Contracts\Template;

class SendMessage implements Command
{
    private $chat;
    private $recipient;
    private $text;
    private $template;

    public function __construct(Chat $chat, User $recipient, string $text = null, Template $template = null)
    {
        if ($text === null && $template === null) {
            throw new InvalidArgumentException('Either text or template should be set.');
        }

        $this->chat = $chat;
        $this->recipient = $recipient;
        $this->text = $text;
        $this->template = $template;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName(): string
    {
        return 'SendMessage';
    }

    public function getChat(): Chat
    {
        return $this->chat;
    }

    public function getRecipient(): User
    {
        return $this->recipient;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function getTemplate(): ?Template
    {
        return $this->template;
    }
}
