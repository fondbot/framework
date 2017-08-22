<?php

declare(strict_types=1);

namespace FondBot\Foundation;

use FondBot\Contracts\Event;
use Illuminate\Http\Request;
use Illuminate\Contracts\Events\Dispatcher;
use FondBot\Drivers\Extensions\WebhookVerification;

class Controller
{
    public function index()
    {
        return 'FondBot v'.Kernel::VERSION;
    }

    public function webhook(Kernel $kernel, Dispatcher $events, Request $request): Event
    {
        $driver = $kernel->getChannel()->getDriver();

        // If driver supports webhook verification
        // We need to check if current request belongs to verification process
        if ($driver instanceof WebhookVerification && $driver->isVerificationRequest()) {
            return $driver->verifyWebhook();
        }

        // Resolve event from driver and dispatch it
        $events->dispatch(
            $event = $driver->createEvent($request)
        );

        return $event;
    }
}
