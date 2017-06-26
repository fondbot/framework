<?php

declare(strict_types=1);

namespace FondBot\Toolbelt\Commands;

use FondBot\Toolbelt\Command;
use GuzzleHttp\ClientInterface;
use FondBot\Drivers\DriverManager;
use Symfony\Component\Console\Helper\Table;

class ListDrivers extends Command
{
    protected function configure(): void
    {
        $this
            ->setName('driver:list')
            ->setDescription('Get list of all available drivers');
    }

    public function handle(): void
    {
        /** @var ClientInterface $http */
        $http = resolve(ClientInterface::class);
        /** @var DriverManager $driverManager */
        $driverManager = resolve(DriverManager::class);

        $installedDrivers = collect($driverManager->all())->keys()->toArray();

        $response = $http->request('GET', 'https://fondbot.com/api/drivers');

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
