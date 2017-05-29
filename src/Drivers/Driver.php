<?php

declare(strict_types=1);

namespace FondBot\Drivers;

use RuntimeException;
use GuzzleHttp\Client;
use FondBot\Helpers\Arr;
use FondBot\Queue\SerializableForQueue;
use FondBot\Http\Request as HttpRequest;
use FondBot\Drivers\Exceptions\InvalidRequest;

abstract class Driver implements SerializableForQueue
{
    /** @var array */
    protected $parameters;

    /** @var HttpRequest */
    protected $request;

    /** @var Client */
    protected $http;

    public function __construct(Client $http)
    {
        $this->http = $http;
    }

    /**
     * Fill driver with parameters and http request instance.
     *
     * @param array       $parameters
     * @param HttpRequest $request
     */
    public function fill(array $parameters, HttpRequest $request): void
    {
        $this->parameters = $parameters;
        $this->request = $request;
    }

    /**
     * @return Client
     */
    public function getHttp(): Client
    {
        return $this->http;
    }

    /**
     * Get parameter value.
     *
     * @param string $name
     * @param null   $default
     *
     * @return mixed
     */
    public function getParameter(string $name, $default = null)
    {
        return Arr::get($this->parameters, $name, $default);
    }

    /**
     * Get template compiler instance.
     *
     * @return TemplateCompiler|null
     */
    abstract public function getTemplateCompiler(): ?TemplateCompiler;

    /**
     * Get command handler instance.
     *
     * @return CommandHandler
     */
    abstract public function getCommandHandler(): CommandHandler;

    /**
     * Verify incoming request data.
     *
     * @throws InvalidRequest
     */
    abstract public function verifyRequest(): void;

    /**
     * Get current chat.
     *
     * @return Chat
     */
    abstract public function getChat(): Chat;

    /**
     * Get current user.
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
     * Handle command.
     *
     * @param Command $command
     *
     * @throws RuntimeException
     */
    public function handle(Command $command): void
    {
        $this->getCommandHandler()->handle($command);
    }
}
