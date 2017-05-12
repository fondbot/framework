<?php

declare(strict_types=1);

namespace FondBot\Toolbelt\Commands;

use FondBot\Toolbelt\Command;
use FondBot\Conversation\ConversationCreator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MakeIntent extends Command
{
    protected function configure(): void
    {
        $this
            ->setName('make:intent')
            ->setDescription('Create a new intent class')
            ->addArgument('name', InputArgument::REQUIRED, 'Name of intent');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');

        /** @var ConversationCreator $creator */
        $creator = $this->kernel->resolve(ConversationCreator::class);

        $creator->createIntent('src', 'App', $name);

        $output->writeln('<comment>Intent created.</comment>');
    }
}
