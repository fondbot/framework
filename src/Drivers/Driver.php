<?php

declare(strict_types=1);

namespace FondBot\Drivers;

use FondBot\Helpers\Arr;
use FondBot\Queue\SerializableForQueue;
use FondBot\Drivers\Exceptions\InvalidRequest;

abstract class Driver implements SerializableForQueue
{
    /** @var array */
    protected $request = [];

    /** @var array */
    protected $headers = [];

    /** @var array */
    protected $parameters;

    /**
     * Set driver data.
     *
     * @param array $parameters
     * @param array $request
     * @param array $headers
     */
    public function fill(array $parameters, array $request = [], array $headers = []): void
    {
        $this->parameters = $parameters;
        $this->request = $request;
        $this->headers = $headers;
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
        return Arr::get($this->request, $key, $default);
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
        return Arr::has($this->request, [$key]);
    }

    /**
     * Get all headers.
     *
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Get header.
     *
     * @param string $name
     * @param null   $default
     *
     * @return mixed
     */
    public function getHeader(string $name, $default = null)
    {
        return Arr::get($this->headers, $name, $default);
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
     * Configuration parameters.
     *
     * @return array
     */
    abstract public function getConfig(): array;

    /**
     * Verify incoming request data.
     *
     * @throws InvalidRequest
     */
    abstract public function verifyRequest(): void;

    /**
     * Get chat.
     *
     * @return Chat
     */
    abstract public function getChat(): Chat;

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
     * Handle command.
     *
     * @param Command $command
     */
    abstract public function handle(Command $command): void;
}
