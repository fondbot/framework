<?php

declare(strict_types=1);

namespace FondBot\Console;

use FondBot\FondBot;
use FondBot\Foundation\Api;
use Illuminate\Console\Command;
use FondBot\Foundation\Composer;

class InstallDriverCommand extends Command
{
    protected $signature = 'fondbot:driver:install 
                            {name : Driver name to be installed}';

    protected $description = 'Install driver';

    private $api;
    private $composer;

    public function __construct(Api $api, Composer $composer)
    {
        parent::__construct();

        $this->api = $api;
        $this->composer = $composer;
    }

    public function handle(): void
    {
        // Check if package is listed in store
        $name = $this->argument('name');
        $driver = $this->api->findDriver($this->argument('name'));

        if ($driver === null) {
            $this->error('"'.$name.'" is not found in the available drivers list or is not yet supported by current FondBot version ('.FondBot::VERSION.').');

            exit(0);
        }

        if ($this->composer->installed($driver['package'])) {
            $this->error('Driver is already installed.');

            return;
        }

        // Install driver
        $this->info('Installing driver...');

        $result = $this->composer->install($driver['package'], function ($_, $line) use (&$output) {
            $output .= $line;
        });

        if ($result !== 0) {
            $this->error($output);
            exit($result);
        }

        $this->info('Driver installed. ✔');
    }
}
