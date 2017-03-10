<?php
declare(strict_types=1);

namespace FondBot;

use FondBot\Channels\Abstracts\Driver;
use FondBot\Conversation\Context;
use FondBot\Conversation\Launcher;
use FondBot\Database\Entities\Channel;
use FondBot\Traits\Loggable;

class Bot
{

    use Loggable;

    public function process(Channel $channel): void
    {
        $request = request();

        /** @var Driver $driver */
        $driver = new $channel->driver($request, $channel->name, $channel->parameters);

        // Initialise driver
        $driver->init();

        // Verify request
        $driver->verifyRequest();

        // Resolve context
        $context = Context::instance($driver);

        /** @var Launcher $conversation */
        $conversation = Launcher::instance($driver, $channel, $context, config('fondbot.stories'));

        // Start conversation
        $conversation->start();
    }

}