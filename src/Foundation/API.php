<?php

declare(strict_types=1);

namespace FondBot\Foundation;

use GuzzleHttp\Client;
use Illuminate\Support\Collection;

class API
{
    public const URL = 'https://fondbot.io/api';

    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Get all available drivers.
     *
     * @return Collection
     */
    public function getDrivers(): Collection
    {
        $response = $this->client->get(self::URL.'/drivers', ['form_params' => ['version' => Kernel::VERSION]]);

        return collect(json_decode((string) $response->getBody(), true));
    }

    /**
     * Find driver by name.
     *
     * @param string $name
     *
     * @return array|null
     */
    public function findDriver(string $name): ?array
    {
        return $this->getDrivers()->first(function ($item) use ($name) {
            return $item['name'] === $name;
        });
    }
}
