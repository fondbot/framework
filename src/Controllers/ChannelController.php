<?php

declare(strict_types=1);

namespace FondBot\Controllers;

use Illuminate\Http\Request;
use FondBot\Channels\ChannelManager;
use FondBot\Foundation\RequestHandler;

class ChannelController
{
    private $manager;

    public function __construct(ChannelManager $manager)
    {
        $this->manager = $manager;
    }

    public function webhook($channel, RequestHandler $handler, Request $request)
    {
        if (is_string($channel)) {
            $channel = $this->manager->get($channel);
        }

        return $handler->handle($channel, $request);
    }
}
