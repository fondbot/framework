<?php

declare(strict_types=1);

namespace FondBot\Conversation;

use FondBot\Channels\Chat;
use FondBot\Channels\User;
use FondBot\Channels\Channel;
use Illuminate\Contracts\Support\Arrayable;

class Context implements Arrayable
{
    private $channel;
    private $chat;
    private $user;
    private $intent;
    private $interaction;
    private $items;
    private $attempts = 0;

    public function __construct(Channel $channel, Chat $chat, User $user, array $items = [])
    {
        $this->channel = $channel;
        $this->chat = $chat;
        $this->user = $user;
        $this->items = collect($items);
    }

    public function getChannel(): Channel
    {
        return $this->channel;
    }

    public function getChat(): Chat
    {
        return $this->chat;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getIntent(): ?Intent
    {
        return $this->intent;
    }

    public function setIntent(Intent $intent): Context
    {
        $this->intent = $intent;

        return $this;
    }

    public function getInteraction(): ?Interaction
    {
        return $this->interaction;
    }

    public function setInteraction(?Interaction $interaction): Context
    {
        $this->interaction = $interaction;

        return $this;
    }

    public function getItem(string $key, $default = null)
    {
        return $this->items->get($key, $default);
    }

    public function setItem(string $key, $value): Context
    {
        $this->items->put($key, $value);

        return $this;
    }

    public function incrementAttempts(): Context
    {
        $this->attempts++;

        return $this;
    }

    public function attempts(): int
    {
        return $this->attempts;
    }

    public function toArray(): array
    {
        return [
            'intent' => $this->intent ? get_class($this->intent) : null,
            'interaction' => $this->interaction ? get_class($this->interaction) : null,
            'items' => $this->items->toArray(),
            'attempts' => $this->attempts,
        ];
    }
}
