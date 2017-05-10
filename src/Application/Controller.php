<?php

declare(strict_types=1);

namespace FondBot\Application;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Controller
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function index(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $response->getBody()->write('FondBot v'.Kernel::VERSION);

        return $response;
    }

    public function webhook(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $name = $args['name'];

        $response->getBody()->write($name);

        return $response;
    }
}
