<?php

declare(strict_types=1);

namespace FondBot\Channels;

use FondBot\Contracts\Database\Entities\Channel;
use FondBot\Traits\Loggable;
use FondBot\Conversation\Keyboard;
use FondBot\Channels\Exceptions\InvalidChannelRequest;

abstract class Driver
{
    use Loggable;

    /** @var array */
    private $request = [];

    /** @var Channel */
    private $channel;

    /** @var array */
    private $parameters;

    /**
     * Set request.
     *
     * @param array $request
     */
    public function setRequest(array $request): void
    {
        $this->request = $request;
    }

    /**
     * Get request value.
     *
     * @param string $name
     * @return mixed
     */
    public function getRequest(string $name)
    {
        return $this->request[$name] ?? null;
    }

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
     * @return mixed
     */
    public function getParameter(string $name)
    {
        return $this->parameters[$name] ?? '';
    }

    /**
     * Set channel parameters.
     *
     * @param array $parameters
     */
    public function setParameters(array $parameters): void
    {
        $this->parameters = $parameters;
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
     * Initialize webhook in the external service.
     *
     * @param string $url
     */
    abstract public function installWebhook(string $url): void;

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
