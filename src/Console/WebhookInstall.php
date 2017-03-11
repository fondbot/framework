<?php
declare(strict_types=1);

namespace FondBot\Console;

use FondBot\Channels\Manager;
use FondBot\Database\Services\ChannelService;
use Illuminate\Console\Command;

class WebhookInstall extends Command
{

    protected $signature = 'fondbot:install-webhook';
    protected $description = 'Install Channel webhook';

    public function handle(Manager $manager)
    {
        $channel = $this->choice('Channel', $this->channels());
        $channel = $this->service()->findByName($channel);

        $url = route('fondbot.webhook', $channel);

        $driver = $manager->driver(request(), $channel);
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