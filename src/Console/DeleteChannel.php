<?php

declare(strict_types=1);

namespace FondBot\Console;

use Illuminate\Console\Command;
use FondBot\Contracts\Database\Services\ChannelService;

class DeleteChannel extends Command
{
    protected $signature = 'fondbot:channel:delete';
    protected $description = 'Delete existing channel';

    public function handle(ChannelService $service)
    {
        $channels = $service->all()->pluck('name', 'id')->toArray();
        $channel = $this->choice('Channel', $channels);

        if (! $this->confirm('Are you sure?')) {
            return;
        }

        $service->delete($service->findByName($channel));

        $this->info('Channel has been deleted.');
    }
}
