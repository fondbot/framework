<?php

declare(strict_types=1);

namespace FondBot\Foundation\Providers;

use FondBot\Events\MessageReceived;
use Illuminate\Contracts\Events\Dispatcher;
use FondBot\Foundation\Listeners\HandleConversation;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        parent::register();

        /** @var Dispatcher $events */
        $events = $this->app['events'];

        $events->listen(MessageReceived::class, HandleConversation::class);
    }
}
