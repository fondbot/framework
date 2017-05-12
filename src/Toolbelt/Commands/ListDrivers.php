<?php

declare(strict_types=1);

namespace FondBot\Toolbelt\Commands;

use FondBot\Toolbelt\Command;
use Symfony\Component\Console\Helper\Table;

class ListDrivers extends Command
{
    protected function configure(): void
    {
        $this
            ->setName('driver:list')
            ->setDescription('Get list of all available drivers.');
    }

    public function handle(): void
    {
        $drivers = collect($this->kernel->getDrivers())
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
