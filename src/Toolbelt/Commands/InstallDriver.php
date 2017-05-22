<?php

declare(strict_types=1);

namespace FondBot\Toolbelt\Commands;

use GuzzleHttp\Client;
use FondBot\Toolbelt\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Console\Input\InputArgument;

class InstallDriver extends Command
{
    protected function configure(): void
    {
        $this
            ->setName('driver:install')
            ->setDescription('Install driver')
            ->addArgument('name', InputArgument::REQUIRED, 'Driver name');
    }

    public function handle(): void
    {
        /** @var Client $http */
        $http = $this->kernel->resolve(Client::class);
        $response = $http->get('https://fondbot.com/api/drivers');
        $items = json_decode($response->getBody()->getContents(), true);

        // Check if package is listed in store
        $name = $this->getArgument('name');
        $drivers = collect($items);

        $driver = $drivers->first(function ($item) use ($name) {
            return $item['name'] === $name;
        });

        if ($driver === null) {
            $this->error('"'.$name.'" is not found in the official drivers list.');
            $package = $this->input('Type composer package name if you know which one you want to install');
        } else {
            // If driver is not official we should ask user to confirm installation
            if ($driver['official'] !== true) {
                if (!$this->confirm('"'.$name.'" is not official. Still want to install?')) {
                    exit;
                }
            }

            $package = $driver['package'];
        }

        // Determine if package is already installed
        $composer = json_decode($this->filesystem()->read('composer.json'), true);
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

        $this->success('Driver installed.');
    }
}
