<?php

declare(strict_types=1);

namespace FondBot\Toolbelt\Commands;

use FondBot\Toolbelt\Command;
use FondBot\Channels\ChannelManager;
use Symfony\Component\Console\Helper\Table;

class ListChannels extends Command
{
    protected function configure(): void
    {
        $this
            ->setName('channel:list')
            ->setDescription('List all channels');
    }

    /**
     * Handle command.
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     */
    public function handle(): void
    {
        /** @var ChannelManager $manager */
        $manager = $this->kernel->resolve(ChannelManager::class);

        $channels = collect($manager->all())
            ->map(function ($item, $name) {
                return [$name, $item['driver'], '/channels/'.$name];
            })
            ->toArray();

        $table = new Table($this->output);
        $table
            ->setHeaders(['Name', 'Driver', 'Route'])
            ->setRows($channels)
            ->render();
    }
}
