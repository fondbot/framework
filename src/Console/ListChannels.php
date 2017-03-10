<?php
declare(strict_types=1);

namespace FondBot\Console;

use FondBot\Channels\Manager;
use FondBot\Database\Entities\Channel;
use Illuminate\Console\Command;

class ListChannels extends Command
{

    protected $signature = 'fondbot:channels';

    public function handle()
    {
        if (Channel::count() === 0) {
            $this->info('No channels.');
            return;
        }

        $rows = [];
        Channel::all()->each(function (Channel $item) use (&$rows) {
            $rows[] = [
                'ID' => $item->id,
                'Driver' => $this->driver($item->driver),
                'Name' => $item->name,
                'Route' => route('fondbot.webhook', [$item]),
                'Enabled' => $item->is_enabled ? 'Yes' : 'No',
                'Updated' => $item->updated_at,
                'Created' => $item->created_at,
            ];
        });

        $this->table(array_keys($rows[0]), $rows);
    }

    private function driver(string $class): string
    {
        $drivers = resolve(Manager::class)->supportedDrivers();
        return collect($drivers)->search(function ($value) use ($class) {
            return $value === $class;
        });
    }

}