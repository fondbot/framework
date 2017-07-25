<?php

declare(strict_types=1);

namespace FondBot\Console;

use Illuminate\Console\Command;
use FondBot\Channels\ChannelManager;

class ListChannels extends Command
{
    protected $signature = 'channel:list';
    protected $description = 'List all channels';

    public function handle(ChannelManager $manager): void
    {
        $rows = collect($manager->all())
            ->map(function ($item, $name) {
                return [$name, $item['driver'], '/channels/'.$name];
            })
            ->toArray();

        $this->table(['Name', 'Driver', 'Route'], $rows);
    }
}
