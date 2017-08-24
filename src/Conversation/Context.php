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
     */
    public function set(string $key, $value): void
    {
        $this->items->put($key, $value);
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->items->toArray();
    }
}
