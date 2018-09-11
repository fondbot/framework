<?php

declare(strict_types=1);

namespace FondBot\Foundation;

use FondBot\FondBot;
use Illuminate\Http\Request;
use Illuminate\Contracts\Events\Dispatcher;
use FondBot\Contracts\Channels\WebhookVerification;

class Controller
{
    public function index(): string
    {
        return 'FondBot v'.FondBot::VERSION;
    }

    public function webhook(FondBot $kernel, Dispatcher $events, Request $request)
    {
        $driver = $kernel->getChannel()->getDriver();

        // If driver supports webhook verification
        // We need to check if current request belongs to verification process
        if ($driver instanceof WebhookVerification && $driver->isVerificationRequest($request)) {
            return $driver->verifyWebhook($request);
        }

        // Resolve event from driver and dispatch it
        $events->dispatch(
            $event = $driver->createEvent($request)
        );

        return $driver->createResponse($request, $event);
    }
}
