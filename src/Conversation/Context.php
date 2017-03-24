<?php

declare(strict_types=1);

namespace FondBot\Conversation;

use FondBot\Contracts\Channels\Sender;
use Illuminate\Contracts\Support\Arrayable;
use FondBot\Contracts\Channels\SenderMessage;
use FondBot\Contracts\Database\Entities\Channel;

class Context implements Arrayable
{
    private $channel;
    private $sender;
    private $message;
    private $story;
    private $interaction;
    private $values;

    public function __construct(
        Channel $channel,
        Sender $sender,
        SenderMessage $message,
        Story $story = null,
        Interaction $interaction = null,
        array $values = []
    ) {
        $this->channel = $channel;
        $this->sender = $sender;
        $this->message = $message;
        $this->story = $story;
        $this->interaction = $interaction;
        $this->values = $values;
    }

    public function getChannel(): Channel
    {
        return $this->channel;
    }

    public function getSender(): Sender
    {
        return $this->sender;
    }

    public function getMessage(): SenderMessage
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
    public function toArray()
    {
        return [
            'story' => $this->story !== null ? get_class($this->story) : null,
            'interaction' => $this->interaction !== null ? get_class($this->interaction) : null,
            'values' => $this->values,
        ];
    }
}
