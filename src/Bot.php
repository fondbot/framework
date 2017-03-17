<?php

declare(strict_types=1);

namespace FondBot;

use FondBot\Channels\Driver;
use FondBot\Traits\Loggable;
use Illuminate\Http\Request;
use FondBot\Jobs\StartConversation;
use FondBot\Channels\ChannelManager;
use FondBot\Contracts\Database\Entities\Channel;
use FondBot\Contracts\Channels\WebhookVerification;

class Bot
{
    use Loggable;

    /** @var array */
    private $request = [];
    /** @var Channel */
    private $channel;
    private $channelManager;

    public function __construct(ChannelManager $channelManager)
    {
        $this->channelManager = $channelManager;
    }

    /**
     * Set request parameters.
     *
     * @param Request $request
     */
    public function setRequest(Request $request): void
    {
        if ($request->isJson()) {
            $this->request = $request->json()->all();
        } else {
            $this->request = $request->all();
        }
    }

    /**
     * Set channel.
     *
     * @param Channel $channel
     */
    public function setChannel(Channel $channel): void
    {
        $this->channel = $channel;
    }

    /**
     * Process webhook request.
     *
     * @return mixed
     */
    public function process()
    {
        $driver = $this->createDriver();

        // Driver has webhook verification
        if($driver instanceof WebhookVerification && $driver->isVerificationRequest()) {
            return $driver->verifyWebhook();
        }

        // Verify request
        $driver->verifyRequest();

        // Send job to start conversation
        $job = (new StartConversation($this->channel, $this->request))
            ->onQueue('fondbot');

        dispatch($job);

        return 'OK';
    }

    /**
     * Create driver instance.
     *
     * @return Driver
     */
    private function createDriver(): Driver
    {
        return $this->channelManager->createDriver($this->request, $this->channel);
    }
}
