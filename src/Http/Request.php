<?php

declare(strict_types=1);

namespace FondBot\Http;

use FondBot\Helpers\Arr;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\ServerRequestInterface;

class Request
{
    private $parameters;
    private $headers;

    public function __construct(array $parameters, array $headers)
    {
        $this->parameters = $parameters;
        $this->headers = $headers;
    }

    public static function fromMessage(MessageInterface $message): Request
    {
        if ($message->getHeaderLine('content-type') === 'application/json') {
            $parameters = (array) json_decode($message->getBody()->getContents(), true);
        } elseif ($message instanceof ServerRequestInterface) {
            $parameters = array_merge($message->getQueryParams(), (array) $message->getParsedBody());
        } else {
            $parameters = (array) json_decode($message->getBody()->getContents(), true);
        }

        return new static($parameters, $message->getHeaders());
    }

    /**
     * Get request parameters.
     *
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

      /**
     * Set request parameters. 
     * 
     * @param array $parameters
     * @return array
     */
    public function setParameters(array $parameters) : array 
    {
        return $this->parameters = $parameters;
    }
    
    /**
     * Get single parameter.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function getParameter(string $key, $default = null)
    {
        return Arr::get($this->parameters, $key, $default);
    }

    /**
     * Determine if request has one or more parameters.
     *
     * @param array|string $keys
     *
     * @return bool
     */
    public function hasParameters($keys): bool
    {
        return Arr::has($this->parameters, (array) $keys);
    }

    /**
     * Get request headers.
     *
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Get single request header.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function getHeader(string $key, $default = null)
    {
        return Arr::get($this->headers, $key, $default);
    }
}
