<?php
declare(strict_types=1);

namespace FondBot;

use FondBot\Channels\ChannelManager;
use FondBot\Channels\Driver;
use FondBot\Database\Entities\Channel;
use FondBot\Jobs\StartConversation;
use FondBot\Traits\Loggable;

class Bot
{
    use Loggable;

    private $channelManager;

    public function __construct(ChannelManager $channelManager)
    {
        $this->channelManager = $channelManager;
    }

    public function process(Channel $channel): void
    {
        /** @var array $request */
        if (request()->isJson()) {
            $request = request()->json()->all();
        } else {
            $request = request()->all();
        }

        /** @var Driver $driver */
        $driver = $this->channelManager->createDriver($request, $channel);

        // Verify request
        $driver->verifyRequest();

        // Send job to start conversation
        $job = (new StartConversation($channel, $request))
            ->onQueue('fondbot-webhook-requests');

        dispatch($job);
    }
}
