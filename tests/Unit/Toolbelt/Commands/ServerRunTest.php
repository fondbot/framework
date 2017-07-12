<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Toolbelt\Commands;

use FondBot\Tests\TestCase;
use FondBot\Toolbelt\Commands\ServerRun;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class ServerRunTest extends TestCase
{
    public function test(): void
    {
        $this->container->add('base_path', __DIR__.'/../..');

        $commandServer = $this->mock(ServerRun::class);
        $commandServer->shouldReceive('setApplication')->once();
        $commandServer->shouldReceive('isEnabled')->once()->andReturn(true);
        $commandServer->shouldReceive('getDefinition')->once()->andReturn(true);
        $commandServer->shouldReceive('getAliases')->once()->andReturn([]);
        $commandServer->shouldReceive('getName')->once()->andReturn('serve');
        $commandServer->shouldReceive('getApplication')->once();
        $commandServer->shouldReceive('run')->once();
        $application = new Application;

        $application->add($commandServer);

        $command = $application->find('serve');
        $commandTester = new CommandTester($command);

        $commandTester->execute([]);
    }
}
