<?php

declare(strict_types=1);

namespace FondBot\Events;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Events\Dispatcher;
use FondBot\Foundation\Listeners\HandleConversation;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        /** @var Dispatcher $events */
        $events = $this->app['events'];

        $events->listen(MessageReceived::class, HandleConversation::class);
    }
}
