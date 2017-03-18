<?php

declare(strict_types=1);

namespace FondBot\Contracts;

trait ContainsRequestInformation
{
    /** @var array */
    private $request = [];

    /** @var array */
    private $headers = [];

    /**
     * Get request value.
     *
     * @param string|null $key
     *
     * @return mixed
     */
    public function getRequest(string $key = null)
    {
        return array_get($this->request, $key);
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
     * If request has key.
     *
     * @param string $key
     *
     * @return bool
     */
    public function hasRequest(string $key): bool
    {
        return array_has($this->request, $key);
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
     *
     * @return mixed
     */
    public function getHeader(string $name)
    {
        return array_get($this->headers, $name);
    }

    /**
     * Set headers.
     *
     * @param array $headers
     */
    public function setHeaders(array $headers): void
    {
        $this->headers = $headers;
    }
}
