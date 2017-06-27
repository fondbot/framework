<?php

declare(strict_types=1);

namespace FondBot\Controllers;

use FondBot\Channels\ChannelManager;
use FondBot\Foundation\RequestHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class WebhookController
{
    private $channelManager;
    private $requestHandler;

    public function __construct(ChannelManager $channelManager, RequestHandler $requestHandler)
    {
        $this->channelManager = $channelManager;
        $this->requestHandler = $requestHandler;
    }

    public function run(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $channel = $this->channelManager->create($args['name']);

        $result = $this->requestHandler->handle($channel, $request);

        if ($result !== null) {
            $response->getBody()->write($result);
        }

        return $response->withStatus(200);
    }
}
