<?php
declare(strict_types=1);

namespace FondBot\Console;

use FondBot\Channels\Abstracts\Driver;
use FondBot\Channels\Manager;
use FondBot\Database\Entities\Channel;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CreateChannel extends Command
{

    protected $signature = 'fondbot:create-channel';
    protected $description = 'Create new channel';

    public function handle()
    {
        $name = $this->ask('Name (e.g. telegram-1)');
        $driver = $this->driver();
        $parameters = $this->parameters($driver);
        $enabled = $this->enabled();

        Channel::create([
            'name' => $name,
            'driver' => $driver,
            'parameters' => $parameters,
            'is_enabled' => $enabled,
        ]);

        $this->info('Channel has been successfully created.');
    }

    private function driver(): string
    {
        $drivers = app(Manager::class)->supportedDrivers();
        $selected = $this->choice('Driver', array_keys($drivers));

        return $drivers[$selected];
    }

    private function parameters(string $driver): array
    {
        /** @var Driver $driver */
        $driver = resolve($driver);

        $parameters = [];

        foreach ($driver->config() as $parameter) {
            $parameters[$parameter] = $this->ask(Str::ucfirst($parameter));
        }

        return $parameters;
    }

    private function enabled(): bool
    {
        return $this->choice('Enable new channel?', ['Yes', 'No'], 1) === 'Yes';
    }

}