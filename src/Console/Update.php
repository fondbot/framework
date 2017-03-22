<?php

declare(strict_types=1);

namespace FondBot\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class Update extends Command
{
    protected $signature = 'fondbot:update';
    protected $description = 'Update FondBot';

    public function handle()
    {
        $this->confirmation();

        (new Filesystem)->delete(config_path('fondbot.php'));

        $this->callSilent('vendor:publish', ['--tag' => 'fondbot']);
        $this->callSilent('migrate', ['--force' => true, '--seed' => true]);
    }

    private function confirmation(): void
    {
        if (!$this->confirm('This command will execute migrations and publish updated configuration file. Are you sure?')) {
            exit;
        }
    }
}
