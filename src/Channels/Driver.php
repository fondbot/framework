<?php

declare(strict_types=1);

namespace FondBot\Channels;

use GuzzleHttp\Client;
use FondBot\Traits\Loggable;
use FondBot\Conversation\Keyboard;
use FondBot\Channels\Objects\Message;
use FondBot\Channels\Objects\Participant;
use FondBot\Channels\Exceptions\InvalidChannelRequest;

abstract class Driver
{
    use Loggable;

    /** @var array */
    protected $request;

    /** @var string */
    private $channelName;

    /** @var array */
    protected $parameters;

    /** @var Client */
    protected $http;

    public function __construct(string $channelName, array $parameters = [], Client $http = null)
    {
        $this->channelName = $channelName;
        $this->parameters = $parameters;
        $this->http = $http;
    }

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
     * Get channel name.
     *
     * @return string
     */
    public function getChannelName(): string
    {
        return $this->channelName;
    }

    /**
     * Get parameter value.
     *
     * @param string $name
     * @return string
     */
    protected function getParameter(string $name): string
    {
        return $this->parameters[$name] ?? '';
    }

    /**
     * Initialize Channel instance.
     */
    abstract public function init(): void;

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
     * Get current participant.
     *
     * @return Participant
     */
    abstract public function getParticipant(): Participant;

    /**
     * Get message sent by participant.
     *
     * @return Message
     */
    abstract public function getMessage(): Message;

    /**
     * Send reply to participant.
     *
     * @param Participant $participant
     * @param Message $message
     * @param Keyboard|null $keyboard
     */
    abstract public function reply(Participant $participant, Message $message, Keyboard $keyboard = null): void;
}
