<?php

declare(strict_types=1);

namespace FondBot\Console;

use GuzzleHttp\Client;
use FondBot\Foundation\Kernel;
use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Illuminate\Contracts\Filesystem\Filesystem;

class InstallDriver extends Command
{
    protected $signature = 'driver:install 
                            {name : Driver name to be installed}';

    public function handle(Client $http, Filesystem $filesystem): void
    {
        $response = $http->request('GET', 'https://fondbot.com/api/drivers', [
            'form_params' => ['version' => Kernel::VERSION],
        ]);

        $items = json_decode((string) $response->getBody(), true);

        // Check if package is listed in store
        $name = $this->argument('name');
        $drivers = collect($items);

        $driver = $drivers->first(function ($item) use ($name) {
            return $item['name'] === $name;
        });

        if ($driver === null) {
            $this->error('"'.$name.'" is not found in the official drivers list.');
            $package = $this->ask('Type composer package name if you know which one you want to install');
        } else {
            // If driver is not official we should ask user to confirm installation
            if ($driver['official'] !== true) {
                if (!$this->confirm('"'.$name.'" is not official. Still want to install?')) {
                    return;
                }
            }

            $package = $driver['package'];
        }

        // Determine if package is already installed
        $composer = json_decode($filesystem->get('composer.json'), true);
        $installed = collect($composer['require'])
            ->merge($composer['require-dev'])
            ->search(function ($_, $item) use ($package) {
                return hash_equals($item, $package);
            });

        if ($installed !== false) {
            $this->error('Driver is already installed.');

            return;
        }

        // Install driver
        $this->info('Installing driver...');

        $process = new Process('composer require '.$package, path());
        $output = '';
        $result = $process->run(function ($_, $line) use (&$output) {
            $output .= $line;
        });

        if ($result !== 0) {
            $this->error($output);
            $this->error('Installation failed.');
            exit($result);
        }

        $this->info('Driver installed.');
    }
}
