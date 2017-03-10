<?php
declare(strict_types=1);

namespace FondBot\Console;

use FondBot\Channels\Manager;
use FondBot\Database\Entities\Channel;
use Illuminate\Console\Command;

class WebhookInstall extends Command
{

    protected $signature = 'fondbot:install-webhook';
    protected $description = 'Install Channel webhook';

    public function handle(Manager $manager)
    {
        $channel = $this->choice('Channel', $this->channels());
        $channel = Channel::where('name', $channel)->first();

        $url = route('fondbot.webhook', $channel);

        $driver = $manager->driver(request(), $channel);
        $driver->installWebhook($url);

        $this->info('Webhook successfully installed.');
    }

    private function channels(): array
    {
        $channels = Channel::where('is_enabled', true)->get();
        if ($channels->count() === 0) {
            $this->error('You have no enabled channels.');
            exit;
        }

        return $channels->pluck('name', 'id')->toArray();
    }

}