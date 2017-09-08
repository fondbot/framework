<?php

declare(strict_types=1);

namespace FondBot\Conversation;

use FondBot\Channels\Chat;
use FondBot\Channels\User;
use FondBot\Channels\Channel;
use Illuminate\Contracts\Support\Arrayable;
use FondBot\Contracts\Conversation\Conversable;

class Context implements Arrayable
{
    private $channel;
    private $chat;
    private $user;
    private $intent;
    private $interaction;
    private $items;

    public function __construct(Channel $channel, Chat $chat, User $user, array $items = [])
    {
        $this->channel = $channel;
        $this->chat = $chat;
        $this->user = $user;
        $this->items = collect($items);
    }

    /**
     * Get channel.
     *
     * @return Channel
     */
    public function getChannel(): Channel
    {
        return $this->channel;
    }

    /**
     * Get chat.
     *
     * @return Chat
     */
    public function getChat(): Chat
    {
        return $this->chat;
    }

    /**
     * Get user.
     *
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * Get current intent.
     *
     * @return Intent|Conversable|null
     */
    public function getIntent(): ?Intent
    {
        return $this->intent;
    }

    /**
     * Set intent.
     *
     * @param Intent $intent
     *
     * @return Context
     */
    public function setIntent(Intent $intent): Context
    {
        $this->intent = $intent;

        return $this;
    }

    /**
     * Get interaction.
     *
     * @return Interaction|Conversable|null
     */
    public function getInteraction(): ?Interaction
    {
        return $this->interaction;
    }

    /**
     * Set interaction.
     *
     * @param Interaction|null $interaction
     *
     * @return Context
     */
    public function setInteraction(?Interaction $interaction): Context
    {
        $this->interaction = $interaction;

        return $this;
    }

    /**
     * Get item.
     *
     * @param string $key
     * @param null   $default
     *
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        return $this->items->get($key, $default);
    }

    /**
     * Set item.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return Context
     */
    public function set(string $key, $value): Context
    {
        $this->items->put($key, $value);

        return $this;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'intent' => $this->intent ? get_class($this->intent) : null,
            'interaction' => $this->interaction ? get_class($this->interaction) : null,
            'items' => $this->items->toArray(),
        ];
    }
}
