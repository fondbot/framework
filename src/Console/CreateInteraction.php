<?php
declare(strict_types=1);

namespace FondBot\Console;

use FondBot\Conversation\ConversationCreator;
use Illuminate\Console\Command;

class CreateInteraction extends Command
{

    protected $signature = 'fondbot:interaction:make {name}';
    protected $description = 'Create a new interaction class';

    public function handle(ConversationCreator $creator)
    {
        $creator->createInteraction($this->argument('name'));

        $this->info('Interaction has been created.');
    }

}