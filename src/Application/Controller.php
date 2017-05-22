<?php

declare(strict_types=1);

namespace FondBot\Application;

use FondBot\Http\Request;
use FondBot\Channels\ChannelManager;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Controller
{
    protected $kernel;

    public function __construct(Kernel $kernel)
    {
        $this->kernel = $kernel;
    }

    public function index(RequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $response->getBody()->write('FondBot v'.Kernel::VERSION);

        return $response;
    }

    public function webhook(RequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        /** @var ChannelManager $channelManager */
        $channelManager = $this->kernel->resolve(ChannelManager::class);

        $channel = $channelManager->create($args['name']);

        $result = $this->kernel->process($channel, Request::fromMessage($request));

        $response->getBody()->write($result);

        return $response;
    }
}
