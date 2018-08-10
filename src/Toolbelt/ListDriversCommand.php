<?php

declare(strict_types=1);

namespace FondBot\Toolbelt;

use FondBot\Foundation\API;
use Illuminate\Console\Command;
use FondBot\Channels\ChannelManager;
use GuzzleHttp\Exception\ClientException;

class ListDriversCommand extends Command
{
    protected $signature = 'fondbot:driver:list';
    protected $description = 'List add installed drivers';

    public function handle(API $api, ChannelManager $manager): void
    {
        try {
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
        } catch (ClientException $exception) {
            $this->error('Connection to FondBot API failed. Please check your internet connection and try again.');
        }
    }
}
