<?php

declare(strict_types=1);

namespace FondBot\Console;

use GuzzleHttp\Client;
use FondBot\Foundation\Kernel;
use Illuminate\Console\Command;
use FondBot\Drivers\DriverManager;
use Symfony\Component\Console\Helper\Table;

class ListDrivers extends Command
{
    protected $signature = 'driver:list';
    protected $description = 'Get list of all available drivers';

    public function handle(Client $http, DriverManager $manager): void
    {
        $installedDrivers = collect($manager->all())->keys()->toArray();

        $response = $http->request('GET', 'https://fondbot.com/api/drivers', [
            'form_params' => ['version' => Kernel::VERSION],
        ]);

        $items = json_decode((string) $response->getBody(), true);

        $drivers = collect($items)
            ->transform(function ($item) use ($installedDrivers) {
                return [
                    $item['name'],
                    $item['package'],
                    $item['official'] ? '✅' : '❌',
                    in_array($item['name'], $installedDrivers, true) ? '✅' : '❌',
                ];
            })
            ->toArray();

        $table = new Table($this->output);
        $table
            ->setHeaders(['Name', 'Package', 'Official', 'Installed'])
            ->setRows($drivers)
            ->render();
    }
}
