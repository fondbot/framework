<?php

declare(strict_types=1);

namespace FondBot\Foundation;

use Illuminate\Http\Request;
use FondBot\Channels\Channel;
use FondBot\Drivers\DriverManager;
use FondBot\Conversation\ConversationManager;
use FondBot\Drivers\Extensions\WebhookVerification;

class RequestHandler
{
    private $kernel;
    private $driverManager;
    private $conversationManager;

    public function __construct(
        Kernel $kernel,
        DriverManager $driverManager,
        ConversationManager $conversationManager
    ) {
        $this->kernel = $kernel;
        $this->driverManager = $driverManager;
        $this->conversationManager = $conversationManager;
    }

    /**
     * @param Channel          $channel
     * @param Request $request
     *
     * @return mixed
     */
    public function handle(Channel $channel, Request $request)
    {
        $driver = $this->driverManager->get($channel->getDriver());

        // Initialize driver
        $driver->initialize($channel, $request);

        $this->kernel->setChannel($channel);
        $this->kernel->setDriver($driver);

        // If driver has webhook verification stuff
        // We need to check if current request belongs to verification process
        if ($driver instanceof WebhookVerification && $driver->isVerificationRequest()) {
            return $driver->verifyWebhook();
        }

        // Verify request consistency
        $driver->verifyRequest();

        // Load session
        $this->kernel->boot($channel, $driver);

        // Process conversation
        $this->conversationManager->handle($driver->getMessage());

        return null;
    }
}
