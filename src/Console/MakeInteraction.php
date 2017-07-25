<?php

declare(strict_types=1);

namespace FondBot\Console;

use Illuminate\Console\Command;
use FondBot\Conversation\ConversationCreator;

class MakeInteraction extends Command
{
    protected $signature = 'make:interaction
                            {name : Name of interaction}';

    protected $description = 'Create a new interaction class';

    public function handle(ConversationCreator $creator): void
    {
        $name = $this->argument('name');

        $creator->createInteraction('src', 'Bot', $name);

        $this->info('Interaction created.');
    }
}
