<?php

declare(strict_types=1);

namespace FondBot\Console;

use Illuminate\Console\Command;
use FondBot\Contracts\Database\Services\ChannelService;

class EnableChannel extends Command
{
    protected $signature = 'fondbot:channel:enable';
    protected $description = 'Enable channel';

    public function handle(ChannelService $service)
    {
        $channels = $service->findDisabled()->pluck('name', 'id')->toArray();

        if (count($channels) === 0) {
            $this->error('No disabled channels.');

            return;
        }

        $channel = $this->choice('Channel', $channels);

        if (! $this->confirm('Are you sure?')) {
            return;
        }

        $service->enable($service->findByName($channel));

        $this->info('Channel enabled.');
    }
}
