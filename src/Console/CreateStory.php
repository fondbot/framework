<?php
declare(strict_types=1);

namespace FondBot\Console;

use FondBot\Conversation\ConversationCreator;
use Illuminate\Console\Command;

class CreateStory extends Command
{

    protected $signature = 'fondbot:create-story {name}';
    protected $description = 'Create new Story';

    public function handle(ConversationCreator $creator)
    {
        $creator->createStory($this->argument('name'));

        $this->info('Story has been created.');
    }

}