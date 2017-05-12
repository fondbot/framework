<?php

declare(strict_types=1);

namespace FondBot\Toolbelt;

use GuzzleHttp\Client;
use League\Container\Container;

class Kernel
{
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function getPath(string $path = ''): string
    {
        return $this->resolve('base_path').'/'.$path;
    }

    /**
     * Resolve from container.
     *
     * @param string $class
     *
     * @return mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     */
    public function resolve(string $class)
    {
        return $this->container->get($class);
    }

    /**
     * Get all available drivers.
     *
     * @return array
     *
     * @throws \RuntimeException
     * @throws \Psr\Container\ContainerExceptionInterface
     */
    public function getDrivers(): array
    {
        /** @var Client $http */
        $http = $this->resolve(Client::class);

        $response = $http->get('https://store.fondbot.com/api/drivers');

        return json_decode($response->getBody()->getContents(), true);
    }
}
