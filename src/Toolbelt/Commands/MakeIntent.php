<?php

declare(strict_types=1);

namespace FondBot\Toolbelt\Commands;

use FondBot\Toolbelt\Command;
use FondBot\Conversation\ConversationCreator;
use Symfony\Component\Console\Input\InputArgument;

class MakeIntent extends Command
{
    protected function configure(): void
    {
        $this
            ->setName('make:intent')
            ->setDescription('Create a new intent class')
            ->addArgument('name', InputArgument::REQUIRED, 'Name of intent');
    }

    public function handle(): void
    {
        $name = $this->getArgument('name');

        /** @var ConversationCreator $creator */
        $creator = resolve(ConversationCreator::class);

        $creator->createIntent('src', 'App', $name);

        $this->success('Intent created.');
    }
}
