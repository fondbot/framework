<?php

declare(strict_types=1);

namespace FondBot\Conversation;

use FondBot\Contracts\Channels\User;
use FondBot\Contracts\Core\Arrayable;
use FondBot\Contracts\Conversation\Story;
use FondBot\Contracts\Channels\ReceivedMessage;
use FondBot\Contracts\Conversation\Interaction;

class Context implements Arrayable
{
    private $channel;
    private $user;
    private $message;
    private $story;
    private $interaction;
    private $values;

    public function __construct(
        string $channel,
        User $user,
        ReceivedMessage $message,
        Story $story = null,
        Interaction $interaction = null,
        array $values = []
    ) {
        $this->channel = $channel;
        $this->user = $user;
        $this->message = $message;
        $this->story = $story;
        $this->interaction = $interaction;
        $this->values = $values;
    }

    public function getChannel(): string
    {
        return $this->channel;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getMessage(): ReceivedMessage
    {
        return $this->message;
    }

    public function getStory(): ?Story
    {
        return $this->story;
    }

    public function setStory(Story $story): void
    {
        $this->story = $story;
    }

    public function getInteraction(): ?Interaction
    {
        return $this->interaction;
    }

    public function setInteraction(?Interaction $interaction): void
    {
        $this->interaction = $interaction;
    }

    public function getValues(): array
    {
        return $this->values;
    }

    public function setValues(array $values): void
    {
        $this->values = $values;
    }

    public function setValue(string $key, $value): void
    {
        $this->values[$key] = $value;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'story' => $this->story !== null ? get_class($this->story) : null,
            'interaction' => $this->interaction !== null ? get_class($this->interaction) : null,
            'values' => $this->values,
        ];
    }
}
