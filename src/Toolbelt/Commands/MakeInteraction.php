<?php

declare(strict_types=1);

namespace FondBot\Toolbelt\Commands;

use FondBot\Toolbelt\Command;
use FondBot\Conversation\ConversationCreator;
use Symfony\Component\Console\Input\InputArgument;

class MakeInteraction extends Command
{
    protected function configure(): void
    {
        $this
            ->setName('make:interaction')
            ->setDescription('Create a new interaction class')
            ->addArgument('name', InputArgument::REQUIRED, 'Name of intent');
    }

    public function handle(): void
    {
        $name = $this->getArgument('name');

        /** @var ConversationCreator $creator */
        $creator = $this->kernel->resolve(ConversationCreator::class);

        $creator->createInteraction('src', 'App', $name);

        $this->success('Interaction created.');
    }
}
