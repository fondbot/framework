<?php

declare(strict_types=1);

namespace FondBot\Toolbelt;

use Illuminate\Console\Command;
use FondBot\Channels\ChannelManager;

class ListChannels extends Command
{
    protected $signature = 'fondbot:channel:list';
    protected $description = 'List all registered channels';

    public function handle(ChannelManager $manager): void
    {
        $rows = collect($manager->all())
            ->transform(function ($item, $name) use ($manager) {
                return [$name, $manager->driver($item['driver'])->getName(), $manager->create($name)->getWebhookUrl()];
            })
            ->toArray();

        $this->table(['Name', 'Driver', 'Webhook URL'], $rows);
    }
}
