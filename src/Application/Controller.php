<?php

declare(strict_types=1);

namespace FondBot\Application;

use FondBot\Http\Request;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use FondBot\Conversation\ConversationManager;

class Controller
{
    public function index(RequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $response->getBody()->write('FondBot v'.Kernel::VERSION);

        return $response;
    }

    public function webhook(RequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        /** @var ConversationManager $conversation */
        $conversation = resolve(ConversationManager::class);

        $result = $conversation->handle($args['name'], Request::fromMessage($request));

        $response->getBody()->write($result);

        return $response;
    }
}
