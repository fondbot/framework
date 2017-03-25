<?php

declare(strict_types=1);

namespace FondBot\Contracts\Channels;

use FondBot\Traits\Loggable;
use FondBot\Contracts\Conversation\Keyboard;
use FondBot\Contracts\ContainsRequestInformation;
use FondBot\Channels\Exceptions\InvalidChannelRequest;

abstract class Driver
{
    use ContainsRequestInformation, Loggable;

    /** @var array */
    private $parameters;

    /**
     * Set parameters.
     *
     * @param array $parameters
     */
    public function setParameters(array $parameters): void
    {
        $this->parameters = $parameters;
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
     * @return SenderMessage
     */
    abstract public function getMessage(): SenderMessage;

    /**
     * Send reply to participant.
     *
     * @param Sender        $sender
     * @param string        $text
     * @param Keyboard|null $keyboard
     *
     * @return ReceiverMessage
     */
    abstract public function sendMessage(Sender $sender, string $text, Keyboard $keyboard = null): ReceiverMessage;
}
