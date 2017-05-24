<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Toolbelt\Commands;

use FondBot\Tests\TestCase;
use Symfony\Component\Console\Application;
use FondBot\Conversation\ConversationCreator;
use FondBot\Toolbelt\Commands\MakeInteraction;
use Symfony\Component\Console\Tester\CommandTester;

class MakeInteractionTest extends TestCase
{
    public function test(): void
    {
        $this->container->add(ConversationCreator::class, $creator = $this->mock(ConversationCreator::class));

        $creator->shouldReceive('createInteraction')->with('src', 'App', 'foo')->once();

        $application = new Application;
        $application->add(new MakeInteraction);

        $command = $application->find('make:interaction');
        $commandTester = new CommandTester($command);
        $commandTester->execute(['command' => $command->getName(), 'name' => 'foo']);

        $this->assertSame('[OK] Interaction created.', trim($commandTester->getDisplay(true)));
    }
}
