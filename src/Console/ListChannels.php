<?php
declare(strict_types=1);

namespace FondBot\Console;

use FondBot\Channels\ChannelManager;
use FondBot\Database\Entities\Channel;
use FondBot\Database\Services\ChannelService;
use FondBot\Nifty\Emoji;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

class ListChannels extends Command
{

    protected $signature = 'fondbot:channel:list 
                           {--enabled : Display only enabled}';
    protected $description = 'List all channels';

    public function handle()
    {
        if ($this->channels()->count() === 0) {
            $this->info('No channels.');
            return;
        }

        $rows = [];
        $this->channels()->each(function (Channel $item) use (&$rows) {
            $rows[] = [
                'ID' => $item->id,
                'Driver' => $this->driver($item->driver),
                'Name' => $item->name,
                'Route' => route('fondbot.webhook', [$item]),
                'Participants' => $item->participants->count(),
                'Enabled' => $item->is_enabled ? Emoji::whiteHeavyCheckMark() : Emoji::crossMark(),
                'Updated' => $item->updated_at,
                'Created' => $item->created_at,
            ];
        });

        $this->table(array_keys($rows[0]), $rows);
    }

    /**
     * @return Channel[]|Collection
     */
    private function channels(): Collection
    {
        /** @var ChannelService $service */
        $service = resolve(ChannelService::class);
        if ($this->option('enabled')) {
            return $service->findEnabled();
        }

        return $service->all();
    }

    private function driver(string $class): string
    {
        $drivers = resolve(ChannelManager::class)->supportedDrivers();
        return collect($drivers)->search(function ($value) use ($class) {
            return $value === $class;
        });
    }

}