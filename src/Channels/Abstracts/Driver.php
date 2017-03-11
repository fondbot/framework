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
    private $name;

    /** @var array */
    protected $parameters;

    /** @var Client */
    protected $http;

    public function __construct(Request $request, string $name = '', array $parameters = [])
    {
        $this->request = $request;
        $this->name = $name;
        $this->parameters = $parameters;
    }

    /**
     * Get channel's name
     *
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * Get parameter value
     *
     * @param string $name
     * @return string
     */
    protected function parameter(string $name): string
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
    abstract public function config(): array;

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

    abstract public function participant(): Participant;

    abstract public function message(): Message;

    abstract public function reply(Participant $participant, Message $message, Keyboard $keyboard = null): void;

}