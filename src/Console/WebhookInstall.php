<?php

declare(strict_types=1);

namespace FondBot\Console;

use Illuminate\Console\Command;
use FondBot\Channels\ChannelManager;
use FondBot\Contracts\Channels\WebhookInstallation;
use FondBot\Contracts\Database\Services\ChannelService;

class WebhookInstall extends Command
{
    protected $signature = 'fondbot:channel:install-webhook';
    protected $description = 'Install webhook';

    public function handle(ChannelManager $manager)
    {
        $channel = $this->choice('Channel', $this->channels());
        $channel = $this->service()->findByName($channel);

        $url = route('fondbot.webhook', $channel);

        $driver = $manager->createDriver([], $channel);

        if (! $driver instanceof WebhookInstallation) {
            $this->error('Driver does support automatic webhook installation.');

            return;
        }

        $driver->installWebhook($url);

        $this->info('Webhook installed.');
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
