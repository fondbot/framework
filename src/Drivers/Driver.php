<?php

declare(strict_types=1);

namespace FondBot\Drivers;

use RuntimeException;
use FondBot\Helpers\Arr;
use FondBot\Http\Request;
use Http\Client\HttpClient;
use Http\Message\RequestFactory;
use Illuminate\Support\Collection;
use Psr\Http\Message\StreamInterface;
use FondBot\Queue\SerializableForQueue;
use Psr\Http\Message\ResponseInterface;
use FondBot\Http\Request as HttpRequest;
use FondBot\Drivers\Exceptions\InvalidRequest;
use FondBot\Contracts\Driver as DriverContract;

abstract class Driver implements DriverContract, SerializableForQueue
{
    /** @var Collection */
    protected $parameters;

    /** @var HttpRequest */
    protected $request;

    /** @var HttpClient */
    private $httpClient;

    /** @var RequestFactory */
    private $requestFactory;

    public function __construct(HttpClient $httpClient, RequestFactory $requestFactory)
    {
        $this->httpClient = $httpClient;
        $this->requestFactory = $requestFactory;
    }

    /**
     * Get driver short name.
     *
     * This name is used as an alias for configuration.
     *
     * @return string
     */
    public function getShortName(): string
    {
        $shortName = explode('\\', get_class($this));

        return collect($shortName)->last();
    }

    /**
     * Initialize gateway with parameters.
     *
     * @param array   $parameters
     *
     * @param Request $request
     *
     * @return Driver|DriverContract|static
     */
    public function initialize(array $parameters, Request $request): DriverContract
    {
        $this->request = $request;

        $array = [];

        foreach ($this->getDefaultParameters() as $key => $value) {
            $value = Arr::get($parameters, $key, $value);

            Arr::set($array, $key, $value);
        }

        $this->parameters = collect($array);

        return $this;
    }

    /**
     * Get parameters.
     *
     * @return Collection
     */
    public function getParameters(): Collection
    {
        return $this->parameters;
    }

    /**
     * Send HTTP request.
     *
     * @param string                                     $method
     * @param string                                     $uri
     * @param array                                      $headers
     * @param string|array|resource|StreamInterface|null $body
     * @param string                                     $protocolVersion
     *
     * @return ResponseInterface
     */
    public function send(
        string $method,
        string $uri,
        array $headers = [],
        $body = null,
        string $protocolVersion = '1.1'
    ): ResponseInterface {
        $request = $this->requestFactory->createRequest($method, $uri, $headers, $body, $protocolVersion);

        return $this->httpClient->sendRequest($request);
    }

    /**
     * Send a GET request.
     *
     * @param string $uri
     * @param array  $headers
     *
     * @return ResponseInterface
     */
    public function get(string $uri, array $headers = []): ResponseInterface
    {
        return $this->send('GET', $uri, $headers);
    }

    /**
     * Send a POST request.
     *
     * @param string                                     $uri
     * @param array                                      $headers
     * @param string|array|resource|StreamInterface|null $body
     *
     * @return ResponseInterface
     */
    public function post(string $uri, array $headers = [], $body = null): ResponseInterface
    {
        return $this->send('POST', $uri, $headers, $body);
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
