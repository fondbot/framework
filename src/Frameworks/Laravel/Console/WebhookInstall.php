<?php

declare(strict_types=1);

namespace FondBot\Frameworks\Laravel\Console;

use Illuminate\Console\Command;
use FondBot\Channels\DriverManager;

class WebhookInstall extends Command
{
    protected $signature = 'fondbot:channel:install-webhook';
    protected $description = 'Install webhook';

    public function handle(DriverManager $manager)
    {
        // TODO
    }
}
