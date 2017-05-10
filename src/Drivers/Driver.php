<?php

declare(strict_types=1);

namespace FondBot\Drivers;

use FondBot\Helpers\Arr;
use FondBot\Queue\SerializableForQueue;
use Psr\Http\Message\ServerRequestInterface;
use FondBot\Drivers\Exceptions\InvalidRequest;

abstract class Driver implements SerializableForQueue
{
    /** @var ServerRequestInterface */
    protected $request = [];

    /** @var array */
    protected $parameters;

    /**
     * Set driver data.
     *
     * @param array $parameters
     * @param ServerRequestInterface $request
     */
    public function fill(array $parameters, ServerRequestInterface $request): void
    {
        $this->parameters = $parameters;
        $this->request = $request;
    }

    /**
     * Get request value.
     *
     * @param string|null $key
     * @param null        $default
     *
     * @return mixed
     */
    public function getRequest(string $key = null, $default = null)
    {
        return Arr::get($this->request->getParsedBody(), $key, $default);
    }

    /**
     * If request has key.
     *
     * @param string $key
     *
     * @return bool
     */
    public function hasRequest(string $key): bool
    {
        return Arr::has($this->request->getParsedBody(), [$key]);
    }

    /**
     * Get header.
     *
     * @param string $name
     * @param null   $default
     *
     * @return mixed
     */
    public function getHeader(string $name = null, $default = null)
    {
        return Arr::get($this->request->getHeaders(), $name, $default);
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
     */
    abstract public function handle(Command $command): void;
}
