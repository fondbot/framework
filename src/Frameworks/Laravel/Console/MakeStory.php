<?php

declare(strict_types=1);

namespace FondBot\Frameworks\Laravel\Console;

use Illuminate\Console\Command;
use FondBot\Conversation\ConversationCreator;

class MakeStory extends Command
{
    protected $signature = 'fondbot:make:story {name}';
    protected $description = 'Create a new story class';

    public function handle(ConversationCreator $creator)
    {
        $creator->createStory($this->argument('name'));

        $this->info('Story has been created.');
    }
}
