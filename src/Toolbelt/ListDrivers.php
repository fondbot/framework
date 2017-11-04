<?php

declare(strict_types=1);

namespace FondBot\Toolbelt;

use FondBot\Foundation\API;
use Illuminate\Console\Command;
use FondBot\Channels\ChannelManager;

class ListDrivers extends Command
{
    protected $signature = 'driver:list';
    protected $description = 'List add installed drivers';

    public function handle(API $api, ChannelManager $manager): void
    {
        $installedDrivers = collect($manager->getDrivers())->keys()->toArray();
        $availableDrivers = $api->getDrivers();

        $rows = collect($availableDrivers)
            ->transform(function ($item) use ($installedDrivers) {
                return [
                    $item['name'],
                    $item['package'],
                    $item['official'] ? '✅' : '❌',
                    in_array($item['name'], $installedDrivers, true) ? '✅' : '❌',
                ];
            })
            ->toArray();

        $this->table(['Name', 'Package', 'Official', 'Installed'], $rows);
    }
}
