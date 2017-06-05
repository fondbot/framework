<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Toolbelt\Commands;

use Mockery;
use Http\Mock\Client;
use Zend\Diactoros\Stream;
use FondBot\Tests\TestCase;
use Http\Client\HttpClient;
use Http\Message\RequestFactory;
use FondBot\Drivers\DriverManager;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
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
        $requestFactory = $this->mock(RequestFactory::class);
        $driverManager = $this->mock(DriverManager::class);

        $httpClient = new Client;
        $this->container->add(HttpClient::class, $httpClient);

        $driverManager->shouldReceive('all')
            ->andReturn([
                'foo' => 'bar',
            ])
            ->once();

        $requestFactory->shouldReceive('createRequest')
            ->with('GET', 'https://fondbot.com/api/drivers')
            ->andReturn($this->mock(RequestInterface::class))
            ->once();

        $stream = fopen('php://memory', 'rb+');
        fwrite($stream, json_encode([['name' => 'foo', 'package' => 'bar', 'official' => true]]));
        rewind($stream);

        $stream = new Stream($stream);

        $response = $this->mock(ResponseInterface::class);
        $response->shouldReceive('getBody')->andReturn($stream)->once();

        $httpClient->addResponse($response);

        $application = new Application;
        $application->add(new ListDrivers);

        $command = $application->find('driver:list');
        $commandTester = new CommandTester($command);
        $commandTester->execute(['command' => $command->getName()]);

        $expected = '+------+---------+----------+-----------+'.PHP_EOL;
        $expected .= '| Name | Package | Official | Installed |'.PHP_EOL;
        $expected .= '+------+---------+----------+-----------+'.PHP_EOL;
        $expected .= '| foo  | bar     | ✅        | ✅         |'.PHP_EOL;
        $expected .= '+------+---------+----------+-----------+'.PHP_EOL;

        $this->assertSame($expected, $commandTester->getDisplay());
    }
}
