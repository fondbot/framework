<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Toolbelt\Commands;

use FondBot\Tests\TestCase;
use FondBot\Channels\ChannelManager;
use Symfony\Component\Console\Application;
use FondBot\Toolbelt\Commands\ListChannels;
use Symfony\Component\Console\Tester\CommandTester;

class ListChannelsTest extends TestCase
{
    public function test(): void
    {
        $this->container->add(ChannelManager::class, $channelManager = $this->mock(ChannelManager::class));

        $channelManager
            ->shouldReceive('all')
            ->andReturn([
                'foo-channel' => ['driver' => 'foo-driver', 'foo' => 'bar'],
            ])
            ->once();

        $application = new Application;
        $application->add(new ListChannels);

        $command = $application->find('channel:list');
        $commandTester = new CommandTester($command);
        $commandTester->execute(['command' => $command->getName()]);

        $expected = '+-------------+------------+-----------------------+'.PHP_EOL;
        $expected .= '| Name        | Driver     | Route                 |'.PHP_EOL;
        $expected .= '+-------------+------------+-----------------------+'.PHP_EOL;
        $expected .= '| foo-channel | foo-driver | /channels/foo-channel |'.PHP_EOL;
        $expected .= '+-------------+------------+-----------------------+'.PHP_EOL;

        $this->assertSame($expected, $commandTester->getDisplay());
    }
}
