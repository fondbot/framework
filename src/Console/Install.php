<?php

declare(strict_types=1);

namespace FondBot\Console;

use Illuminate\Console\Command;

class Install extends Command
{
    protected $signature = 'fondbot:install 
                            {--fresh-migrations : Refresh database} 
                            {--force : Force FondBot installation}';
    protected $description = 'Install FondBot';

    public function handle()
    {
        $this->confirmation();

        $this->assets();
        $this->migrations();

        $this->info('FondBot has been installed.');
    }

    private function confirmation(): void
    {
        if ($this->option('force') === true) {
            return;
        }

        if (!$this->confirm('This command will erase some of your files and install fresh copy of FondBot. Are you sure?')) {
            exit;
        }
    }

    private function assets(): void
    {
        $this->callSilent('vendor:publish', ['--tag' => 'fondbot']);
    }

    private function migrations(): void
    {
        if ($this->option('fresh-migrations')) {
            $this->callSilent('migrate:refresh', ['--force' => true, '--seed' => true]);
        } else {
            $this->callSilent('migrate', ['--force' => true, '--seed' => true]);
        }
    }
}
