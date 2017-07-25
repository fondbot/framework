<?php

declare(strict_types=1);

namespace FondBot\Console;

use Illuminate\Console\Command;
use FondBot\Conversation\ConversationCreator;

class MakeIntent extends Command
{
    protected $signature = 'make:intent
                            {name : Name of intent}';

    protected $description = 'Create a new intent class';

    public function handle(ConversationCreator $creator): void
    {
        $name = $this->argument('name');

        $creator->createIntent('src', 'Bot', $name);

        $this->info('Intent created.');
    }
}
