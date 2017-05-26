<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Toolbelt\Commands;

use FondBot\Tests\TestCase;
use FondBot\Toolbelt\Commands\MakeIntent;
use Symfony\Component\Console\Application;
use FondBot\Conversation\ConversationCreator;
use Symfony\Component\Console\Tester\CommandTester;

class MakeIntentTest extends TestCase
{
    public function test(): void
    {
        $this->container->add(ConversationCreator::class, $creator = $this->mock(ConversationCreator::class));

        $creator->shouldReceive('createIntent')->with('src', 'Bot', 'foo')->once();

        $application = new Application;
        $application->add(new MakeIntent);

        $command = $application->find('make:intent');
        $commandTester = new CommandTester($command);
        $commandTester->execute(['command' => $command->getName(), 'name' => 'foo']);

        $this->assertSame('[OK] Intent created.', trim($commandTester->getDisplay(true)));
    }
}
