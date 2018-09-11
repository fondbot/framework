<?php

declare(strict_types=1);

namespace FondBot\Console;

use Illuminate\Console\Command;
use FondBot\Channels\ChannelManager;

class ListChannelsCommand extends Command
{
    protected $signature = 'fondbot:channel:list';
    protected $description = 'List all registered channels';

    private $channelManager;

    public function __construct(ChannelManager $channelManager)
    {
        parent::__construct();

        $this->channelManager = $channelManager;
    }

    public function handle(): void
    {
        $rows = collect($this->channelManager->all())
            ->transform(function ($item, $name) {
                return [
                    $name,
                    $this->channelManager->driver($item['driver'])->getName(),
                    $this->channelManager->create($name)->getWebhookUrl(),
                ];
            })
            ->toArray();

        $this->table(['Name', 'Driver', 'Webhook URL'], $rows);
    }
}
