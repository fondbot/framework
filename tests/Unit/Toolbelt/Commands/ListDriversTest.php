<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Toolbelt\Commands;

use Mockery;
use GuzzleHttp\Client;
use FondBot\Tests\TestCase;
use GuzzleHttp\Psr7\Stream;
use FondBot\Toolbelt\Commands\ListDrivers;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class ListDriversTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Mockery::getConfiguration()->allowMockingNonExistentMethods(true);
    }

    public function test(): void
    {
        $this->container->add(Client::class, $guzzle = $this->mock(Client::class));

        $stream = fopen('php://memory', 'rb+');
        fwrite($stream, json_encode([['name' => 'foo', 'package' => 'bar', 'official' => true]]));
        rewind($stream);

        $guzzle->shouldReceive('get')
            ->with('https://fondbot.io/api/drivers')
            ->andReturnSelf()
            ->once();
        $guzzle->shouldReceive('getBody')
            ->andReturn($stream = new Stream($stream))
            ->once();

        $application = new Application;
        $application->add(new ListDrivers);

        $command = $application->find('driver:list');
        $commandTester = new CommandTester($command);
        $commandTester->execute(['command' => $command->getName()]);

        $expected = '+------+---------+----------+'.PHP_EOL;
        $expected .= '| Name | Package | Official |'.PHP_EOL;
        $expected .= '+------+---------+----------+'.PHP_EOL;
        $expected .= '| foo  | bar     | ✅        |'.PHP_EOL;
        $expected .= '+------+---------+----------+'.PHP_EOL;

        $this->assertSame($expected, $commandTester->getDisplay());
    }
}
