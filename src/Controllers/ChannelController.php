<?php

declare(strict_types=1);

namespace FondBot\Controllers;

use FondBot\Foundation\Kernel;
use Illuminate\Contracts\Events\Dispatcher;
use FondBot\Drivers\Extensions\WebhookVerification;

class ChannelController
{
    private $kernel;
    private $events;

    public function __construct(Kernel $kernel, Dispatcher $events)
    {
        $this->kernel = $kernel;
        $this->events = $events;
    }

    public function webhook()
    {
        $driver = $this->kernel->getDriver();

        // If driver supports webhook verification
        // We need to check if current request belongs to verification process
        if ($driver instanceof WebhookVerification && $driver->isVerificationRequest()) {
            return $driver->verifyWebhook();
        }

        $event = $this->kernel->getEvent();

        $this->events->dispatch($event);

        return $event;
    }
}
