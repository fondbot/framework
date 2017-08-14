<?php

declare(strict_types=1);

namespace FondBot\Foundation;

use FondBot\Events\EventServiceProvider;
use FondBot\Channels\ChannelServiceProvider;
use Illuminate\Support\AggregateServiceProvider;
use FondBot\Conversation\ConversationServiceProvider;

class ServiceProvider extends AggregateServiceProvider
{
    protected $providers = [
        ChannelServiceProvider::class,
        ConversationServiceProvider::class,
        EventServiceProvider::class,
    ];
}
