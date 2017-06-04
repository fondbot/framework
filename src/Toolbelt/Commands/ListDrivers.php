<?php

declare(strict_types=1);

namespace FondBot\Toolbelt\Commands;

use Http\Client\HttpClient;
use FondBot\Toolbelt\Command;
use Http\Message\RequestFactory;
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
        /** @var HttpClient $http */
        $http = resolve(HttpClient::class);
        /** @var RequestFactory $requestFactory */
        $requestFactory = resolve(RequestFactory::class);

        $request = $requestFactory->createRequest('GET', 'https://fondbot.com/api/drivers');

        $response = $http->sendRequest($request);

        $items = json_decode((string) $response->getBody(), true);

        $drivers = collect($items)
            ->map(function ($item) {
                return [$item['name'], $item['package'], $item['official'] ? '✅' : '❌'];
            })
            ->toArray();

        $table = new Table($this->output);
        $table
            ->setHeaders(['Name', 'Package', 'Official'])
            ->setRows($drivers)
            ->render();
    }
}
