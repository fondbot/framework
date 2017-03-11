<?php
declare(strict_types=1);

namespace FondBot\Console;

use FondBot\Database\Services\ChannelService;
use Illuminate\Console\Command;

class DeleteChannel extends Command
{

    protected $signature = 'fondbot:delete-channel';
    protected $description = 'Delete channel';

    public function handle(ChannelService $service)
    {
        $channels = $service->all()->pluck('name', 'id')->toArray();
        $channel = $this->choice('Channel', $channels);

        if (!$this->confirm('Are you sure?')) {
            return;
        }

        $service->delete($service->findByName($channel));

        $this->info('Channel has been deleted.');
    }

}