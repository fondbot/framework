<?php

declare(strict_types=1);

namespace FondBot\Contracts\Channels;

use FondBot\Traits\Loggable;
use FondBot\Conversation\Keyboard;
use FondBot\Contracts\Database\Entities\Channel;
use FondBot\Contracts\ContainsRequestInformation;
use FondBot\Channels\Exceptions\InvalidChannelRequest;

abstract class Driver
{
    use ContainsRequestInformation, Loggable;

    /** @var Channel */
    private $channel;

    /** @var array */
    private $parameters;

    /**
     * Set channel name.
     *
     * @param Channel $channel
     */
    public function setChannel(Channel $channel): void
    {
        $this->channel = $channel;
        $this->parameters = $channel->parameters;
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
     * Get parameter value.
     *
     * @param string $name
     *
     * @return mixed
     */
    public function getParameter(string $name)
    {
        return $this->parameters[$name] ?? '';
    }

    /**
     * Configuration parameters.
     *
     * @return array
     */
    abstract public function getConfig(): array;

    /**
     * Verify incoming request data.
     *
     * @throws InvalidChannelRequest
     */
    abstract public function verifyRequest(): void;

    /**
     * Get message sender.
     *
     * @return Sender
     */
    abstract public function getSender(): Sender;

    /**
     * Get message received from sender.
     *
     * @return Message
     */
    abstract public function getMessage(): Message;

    /**
     * Send reply to participant.
     *
     * @param Receiver $receiver
     * @param string $text
     * @param Keyboard|null $keyboard
     */
    abstract public function sendMessage(Receiver $receiver, string $text, Keyboard $keyboard = null): void;
}
