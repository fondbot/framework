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
     * Process webhook request.
     *
     * @param Channel $channel
     */
    public function process(Channel $channel): void
    {
        // Verify request
        $this->createDriver($channel)->verifyRequest();

        // Send job to start conversation
        $job = (new StartConversation($channel, $this->request))
            ->onQueue('fondbot');

        dispatch($job);
    }

    /**
     * Verify webhook and respond something based on driver.
     *
     * @param Channel $channel
     * @return mixed
     */
    public function verify(Channel $channel)
    {
        $driver = $this->createDriver($channel);

        // Verification is not required
        if (! $driver instanceof WebhookVerification) {
            return ['response' => 'OK'];
        }

        return $driver->verifyWebhook();
    }

    /**
     * Create driver instance.
     *
     * @param Channel $channel
     * @return Driver
     */
    private function createDriver(Channel $channel): Driver
    {
        return $this->channelManager->createDriver($this->request, $channel);
    }
}
