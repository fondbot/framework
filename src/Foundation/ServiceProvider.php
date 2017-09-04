<?php

declare(strict_types=1);

namespace FondBot\Foundation;

use FondBot\Events\EventServiceProvider;
use FondBot\Channels\ChannelServiceProvider;
use FondBot\Toolbelt\ToolbeltServiceProvider;
use Illuminate\Support\AggregateServiceProvider;
use FondBot\Conversation\ConversationServiceProvider;
use FondBot\Foundation\Providers\RouteServiceProvider;

class ServiceProvider extends AggregateServiceProvider
{
    protected $providers = [
        ChannelServiceProvider::class,
        ConversationServiceProvider::class,
        EventServiceProvider::class,
        ToolbeltServiceProvider::class,
        RouteServiceProvider::class,
    ];
}
