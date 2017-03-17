<?php

declare(strict_types=1);

namespace FondBot\Console;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use FondBot\Channels\ChannelManager;
use FondBot\Contracts\Channels\Driver;
use FondBot\Contracts\Database\Services\ChannelService;

class CreateChannel extends Command
{
    protected $signature = 'fondbot:channel:create';
    protected $description = 'Create a new channel';

    public function handle(ChannelService $service)
    {
        $name = $this->ask('Name (e.g. telegram-1)');
        $driver = $this->driver();
        $parameters = $this->parameters($driver);
        $enabled = $this->enabled();

        $service->create([
            'name' => $name,
            'driver' => $driver,
            'parameters' => $parameters,
            'is_enabled' => $enabled,
        ]);

        $this->info('Channel has been successfully created.');
    }

    private function driver(): string
    {
        $drivers = app(ChannelManager::class)->supportedDrivers();
        $selected = $this->choice('Driver', array_keys($drivers));

        return $drivers[$selected];
    }

    private function parameters(string $driver): array
    {
        /** @var Driver $driver */
        $driver = resolve($driver);

        $parameters = [];

        foreach ($driver->getConfig() as $parameter) {
            $parameters[$parameter] = $this->ask(Str::ucfirst($parameter));
        }

        return $parameters;
    }

    private function enabled(): bool
    {
        return $this->choice('Enable new channel?', ['Yes', 'No'], 1) === 'Yes';
    }
}
