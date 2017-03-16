<?php

declare(strict_types=1);

namespace FondBot\Console;

use Illuminate\Console\Command;
use FondBot\Contracts\Database\Services\ChannelService;

class DisableChannel extends Command
{
    protected $signature = 'fondbot:channel:disable';
    protected $description = 'Disable channel';

    public function handle(ChannelService $service)
    {
        $channels = $service->findEnabled()->pluck('name', 'id')->toArray();

        if (count($channels) === 0) {
            $this->error('No enabled channels.');

            return;
        }

        $channel = $this->choice('Channel', $channels);

        if (! $this->confirm('Are you sure?')) {
            return;
        }

        $service->disable($service->findByName($channel));

        $this->info('Channel disabled.');
    }
}
