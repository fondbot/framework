<?php

declare(strict_types=1);

namespace FondBot\Toolbelt;

use FondBot\Foundation\API;
use FondBot\Foundation\Kernel;
use Illuminate\Console\Command;
use FondBot\Foundation\Composer;

class InstallDriver extends Command
{
    protected $signature = 'driver:install 
                            {name : Driver name to be installed}';

    protected $description = 'Install driver';

    public function handle(API $api, Composer $composer): void
    {
        // Check if package is listed in store
        $name = $this->argument('name');
        $driver = $api->findDriver($this->argument('name'));

        if ($driver === null) {
            $this->error('"'.$name.'" is not found in the available drivers list or is not yet supported by current FondBot version ('.Kernel::VERSION.').');

            exit(0);
        }

        if ($composer->installed($driver['package'])) {
            $this->error('Driver is already installed.');

            return;
        }

        // Install driver
        $this->info('Installing driver...');

        $result = $composer->install($driver['package'], function ($_, $line) use (&$output) {
            $output .= $line;
        });

        if ($result !== 0) {
            $this->error($output);
            exit($result);
        }

        $this->info('Driver installed. ✔');
    }
}
