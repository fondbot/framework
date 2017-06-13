<?php

declare(strict_types=1);

namespace FondBot\Contracts;

use Psr\Http\Message\RequestInterface;

interface Driver
{
    /**
     * Get gateway display name.
     *
     * This can be used for various system where human-friendly name is required.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Get driver short name.
     *
     * This name is used as an alias for configuration.
     *
     * @return string
     */
    public function getShortName(): string;

    /**
     * Define driver default parameters.
     *
     * Example: ['token' => '', 'apiVersion' => '1.0']
     *
     * @return array
     */
    public function getDefaultParameters(): array;

    /**
     * Initialize gateway with request and parameters.
     *
     * @param array            $parameters
     * @param RequestInterface $request
     *
     * @return Driver|static
     */
    public function initialize(array $parameters, RequestInterface $request): Driver;
}
