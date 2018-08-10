<?php

declare(strict_types=1);

namespace FondBot\Toolbelt;

use Illuminate\Console\Command;
use FondBot\Contracts\Conversation\Manager;

class ListIntentsCommand extends Command
{
    protected $signature = 'fondbot:intent:list';
    protected $description = 'List all registered intents';

    public function handle(Manager $manager): void
    {
        $rows = collect($manager->getIntents())
            ->transform(function ($item) {
                return [$item];
            })
            ->toArray();

        $this->table(['Class'], $rows);
    }
}
