<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Toolbelt\Commands;

use Monolog\Logger;
use FondBot\Tests\TestCase;
use FondBot\Toolbelt\Commands\Log;
use Monolog\Handler\StreamHandler;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class LogTest extends TestCase
{
    public function test_stream_handler_exists(): void
    {
        $this->container->add(Logger::class, $logger = $this->mock(Logger::class));

        $logger->shouldReceive('getHandlers')->andReturn([$handler = $this->mock(StreamHandler::class)])->once();
        $handler->shouldReceive('getUrl')->andReturn('foo')->once();

        $application = new Application;
        $application->add(new Log);

        $command = $application->find('log');
        $commandTester = new CommandTester($command);
        $commandTester->execute(['command' => $command->getName()]);

        $this->assertTrue(in_array(
            trim($commandTester->getDisplay(true)),
            ['tail: cannot open `foo\' for reading: No such file or directory', 'tail: foo: No such file or directory'],
            true
        ));
    }

    public function test_without_stream_handler(): void
    {
        $this->container->add(Logger::class, $logger = $this->mock(Logger::class));

        $logger->shouldReceive('getHandlers')->andReturn([])->once();

        $application = new Application;
        $application->add(new Log);

        $command = $application->find('log');
        $commandTester = new CommandTester($command);
        $commandTester->execute(['command' => $command->getName()]);

        $this->assertSame('There are no logs stored in filesystem.', trim($commandTester->getDisplay(true)));
    }
}
