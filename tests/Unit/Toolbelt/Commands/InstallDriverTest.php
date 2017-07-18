<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Toolbelt\Commands;

use GuzzleHttp\Client;
use FondBot\Tests\TestCase;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use League\Flysystem\MountManager;
use GuzzleHttp\Handler\MockHandler;
use League\Flysystem\FilesystemInterface;
use Symfony\Component\Console\Application;
use FondBot\Toolbelt\Commands\InstallDriver;
use Symfony\Component\Console\Tester\CommandTester;

class InstallDriverTest extends TestCase
{
    public function testCommandExistInstalled() : void
    {
        $randomString = $this->faker()->word;

        $mock = new MockHandler([
            new Response(200, [], $this->jsonDrivers()),
        ]);
        $handler = HandlerStack::create($mock);
        $mountManager = $this->mock(MountManager::class);
        $this->container->add(Client::class, new Client(['handler' => $handler]));
        $this->container->add(MountManager::class, $mountManager);
        $this->container->add('base_path', __DIR__);
        $fileInterface = $this->mock(FilesystemInterface::class);
        $fileInterface->shouldReceive('read')
                      ->once()->with('composer.json')->andReturn(file_get_contents('./composer.json'));
        $mountManager->shouldReceive('getFilesystem')->once()->with('local')->andReturn($fileInterface);

        $application = new Application;
        $application->add(new InstallDriver());

        $command = $application->find('driver:install');
        $commandTester = new CommandTester($command);
        $commandTester->setInputs(['php']);
        $commandTester->execute(['name' => $randomString]);

        $this->assertRegExp(
            '~is not found in the official drivers list|
                    Type composer package name if you know which one you want to install:|
                    river is already installed.~',
            $commandTester->getDisplay()
        );
    }

    public function testCommandNotOfficial() : void
    {
        $mock = new MockHandler([
            new Response(200, [], $this->jsonDriversNotOfficial()),
        ]);
        $handler = HandlerStack::create($mock);
        $this->container->add(Client::class, new Client(['handler' => $handler]));

        $this->container->add('base_path', __DIR__);

        $application = new Application;
        $application->add(new InstallDriver());

        $command = $application->find('driver:install');
        $commandTester = new CommandTester($command);
        $commandTester->setInputs(['no']);
        $commandTester->execute(['name' => 'telegram']);

        $this->assertRegExp(
            '~is not official\. Still want to install\? \(yes\/no\) \[no\]:~',
            $commandTester->getDisplay()
        );
    }

    private function jsonDrivers() : string
    {
        return "[{\"name\":\"telegram\",\"package\":\"fondbot\/telegram\",
        \"repository\":\"http:\/\/github.com\/fondbot\/drivers-telegram\",
        \"official\":true,\"versions\":[\"1.0\"]},{\"name\":\"facebook\",
        \"package\":\"fondbot\/facebook\",\"repository\":\"http:\/\/github.com\/fondbot\/drivers-facebook\",
        \"official\":true,\"versions\":[\"1.0\"]},{\"name\":\"vk-communities\",
        \"package\":\"fondbot\/vk-communities\",
        \"repository\":\"https:\/\/github.com\/fondbot\/drivers-vk-communities\",
        \"official\":true,\"versions\":[\"1.0\"]}]";
    }

    private function jsonDriversNotOfficial($driver = 'telegram') : string
    {
        return "[{\"name\":\"telegram\",\"package\":\"fondbot\/telegram\",
        \"repository\":\"http:\/\/github.com\/fondbot\/drivers-telegram\",
        \"official\":false,\"versions\":[\"1.0\"]},{\"name\":\"facebook\",
        \"package\":\"fondbot\/facebook\",\"repository\":\"http:\/\/github.com\/fondbot\/drivers-facebook\",
        \"official\":false,\"versions\":[\"1.0\"]},{\"name\":\"vk-communities\",
        \"package\":\"fondbot\/vk-communities\",
        \"repository\":\"https:\/\/github.com\/fondbot\/drivers-vk-communities\",
        \"official\":false,\"versions\":[\"1.0\"]}]";
    }
}
