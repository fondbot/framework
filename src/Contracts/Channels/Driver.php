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
     * Get user.
     *
     * @return User
     */
    abstract public function getUser(): User;

    /**
     * Get message received from sender.
     *
     * @return ReceivedMessage
     */
    abstract public function getMessage(): ReceivedMessage;

    /**
     * Send reply to participant.
     *
     * @param User          $sender
     * @param string        $text
     * @param Keyboard|null $keyboard
     *
     * @return OutgoingMessage
     */
    abstract public function sendMessage(User $sender, string $text, Keyboard $keyboard = null): OutgoingMessage;
}
