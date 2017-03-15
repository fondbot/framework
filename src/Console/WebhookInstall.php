<?php

declare(strict_types=1);

namespace FondBot\Console;

use Illuminate\Console\Command;
use FondBot\Channels\ChannelManager;
use FondBot\Database\Services\ChannelService;

class WebhookInstall extends Command
{
    protected $signature = 'fondbot:channel:install-webhook';
    protected $description = 'Install webhook';

    public function handle(ChannelManager $manager)
    {
        $channel = $this->choice('Channel', $this->channels());
        $channel = $this->service()->findByName($channel);

        $url = route('fondbot.webhook', $channel);

        $driver = $manager->createDriver(request(), $channel);
        $driver->installWebhook($url);

        $this->info('Webhook successfully installed.');
    }

    private function channels(): array
    {
        /** @var ChannelService $service */
        $channels = $this->service()->findEnabled();
        if ($channels->count() === 0) {
            $this->error('You have no enabled channels.');
            exit;
        }

        return $channels->pluck('name', 'id')->toArray();
    }

    private function service(): ChannelService
    {
        return resolve(ChannelService::class);
    }
}
