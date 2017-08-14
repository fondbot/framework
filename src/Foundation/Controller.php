<?php

declare(strict_types=1);

namespace FondBot\Foundation;

use FondBot\Contracts\Event;
use Illuminate\Contracts\Events\Dispatcher;
use FondBot\Drivers\Extensions\WebhookVerification;

class Controller
{
    public function index()
    {
        return 'FondBot v'.Kernel::VERSION;
    }

    public function webhook(Kernel $kernel, Dispatcher $events): Event
    {
        $driver = $kernel->getDriver();

        // If driver supports webhook verification
        // We need to check if current request belongs to verification process
        if ($driver instanceof WebhookVerification && $driver->isVerificationRequest()) {
            return $driver->verifyWebhook();
        }

        $event = $kernel->getEvent();

        $events->dispatch($event);

        return $event;
    }
}
