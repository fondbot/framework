<?php

declare(strict_types=1);

namespace FondBot\Console;

use FondBot\Foundation\Api;
use Illuminate\Console\Command;
use FondBot\Channels\ChannelManager;
use GuzzleHttp\Exception\ClientException;

class ListDriversCommand extends Command
{
    protected $signature = 'fondbot:driver:list';
    protected $description = 'List add installed drivers';

    private $api;
    private $channelManager;

    public function __construct(Api $api, ChannelManager $channelManager)
    {
        parent::__construct();

        $this->api = $api;
        $this->channelManager = $channelManager;
    }

    public function handle(): void
    {
        try {
            $installedDrivers = collect($this->channelManager->getDrivers())->keys()->toArray();
            $availableDrivers = $this->api->getDrivers();

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
