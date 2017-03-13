<?php declare(strict_types=1);

namespace FondBot\Channels\Abstracts;

use FondBot\Channels\Exceptions\InvalidChannelRequest;
use FondBot\Channels\Objects\Message;
use FondBot\Channels\Objects\Participant;
use FondBot\Conversation\Keyboard;
use FondBot\Traits\Loggable;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

abstract class Driver
{

    use Loggable;

    /** @var Request */
    protected $request;

    /** @var string */
    private $channelName;

    /** @var array */
    protected $parameters;

    /** @var Client */
    protected $http;

    public function __construct(Request $request, string $channelName, array $parameters = [], Client $http = null)
    {
        $this->request = $request;
        $this->channelName = $channelName;
        $this->parameters = $parameters;
        $this->http = $http;
    }

    /**
     * Get channel name
     *
     * @return string
     */
    public function getChannelName(): string
    {
        return $this->channelName;
    }

    /**
     * Get parameter value
     *
     * @param string $name
     * @return string
     */
    protected function getParameter(string $name): string
    {
        return $this->parameters[$name] ?? '';
    }

    /**
     * Initialize Channel instance
     */
    abstract public function init(): void;

    /**
     * Configuration parameters
     *
     * @return array
     */
    abstract public function getConfig(): array;

    /**
     * Verify incoming request data
     *
     * @throws InvalidChannelRequest
     */
    abstract public function verifyRequest(): void;

    /**
     * Initialize webhook in the external service
     *
     * @param string $url
     */
    abstract public function installWebhook(string $url): void;

    /**
     * Get current participant
     *
     * @return Participant
     */
    abstract public function getParticipant(): Participant;

    /**
     * Get message sent by participant
     *
     * @return Message
     */
    abstract public function getMessage(): Message;

    /**
     * Send reply to participant
     *
     * @param Participant $participant
     * @param Message $message
     * @param Keyboard|null $keyboard
     */
    abstract public function reply(Participant $participant, Message $message, Keyboard $keyboard = null): void;

}