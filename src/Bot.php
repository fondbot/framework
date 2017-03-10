<?php
declare(strict_types=1);

namespace FondBot;

use FondBot\Channels\Abstracts\Driver;
use FondBot\Channels\Manager as ChannelManager;
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

        /** @var ChannelManager $manager */
        $manager = resolve(ChannelManager::class);

        /** @var Driver $driver */
        $driver = $manager->driver($request, $channel);

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