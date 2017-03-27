<?php

declare(strict_types=1);

namespace FondBot\Frameworks\Laravel\Console;

use Illuminate\Console\Command;
use FondBot\Conversation\ConversationCreator;

class MakeInteraction extends Command
{
    protected $signature = 'fondbot:make:interaction {name}';
    protected $description = 'Create a new interaction class';

    public function handle(ConversationCreator $creator)
    {
        $creator->createInteraction($this->argument('name'));

        $this->info('Interaction has been created.');
    }
}
