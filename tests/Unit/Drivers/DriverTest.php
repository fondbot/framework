<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Drivers;

use Mockery\Mock;
use GuzzleHttp\Client;
use FondBot\Drivers\Driver;
use FondBot\Tests\TestCase;
use GuzzleHttp\HandlerStack;
use Illuminate\Http\Request;
use FondBot\Channels\Channel;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Handler\MockHandler;

class DriverTest extends TestCase
{
    /**
     * @var Client
     */
    protected $guzzle;

    public function setUp() : void
    {
        $this->markTestIncomplete();
        parent::setUp();
        $this->guzzle = $this->mock(Client::class);
    }

    public function testInitialize(): void
    {
        $channel = $this->mock(Channel::class);
        $request = Request::create('/');

        /** @var Driver|Mock $driver */
        $driver = $this->mock(Driver::class)->makePartial();

        $channel->shouldReceive('getParameters')->andReturn(['foo' => 'bar'])->atLeast()->once();
        $driver->shouldReceive('getDefaultParameters')->andReturn(['foo' => '', 'bar' => ''])->once();

        $driver = $driver->initialize($channel, $request);

        $this->assertSame('bar', $driver->getParameters()['foo']);
        $this->assertSame('', $driver->getParameters()['bar']);
    }

    public function testPostMethod() : void
    {
        $string = $this->faker()->randomLetter;
        $mock = new MockHandler([
            new Response(200, []),
        ]);

        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);
        $driver = $this->createExtendDriver($client);
        $driver->post($string);
    }

    public function testGetMethod() : void
    {
        $string = $this->faker()->randomLetter;
        $mock = new MockHandler([
            new Response(200, []),
        ]);

        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);
        $driver = $this->createExtendDriver($client);
        $driver->get($string);
    }

    public function testGetShortName() : void
    {
        $driver = $this->createExtendDriver();
        $driver->getShortName();
    }

    public function testGetRequestJson() : void
    {
        $channel = $this->mock(Channel::class);
        $request = Request::create('/');

        /** @var Driver|Mock $driver */
        $driver = $this->mock(Driver::class)->makePartial();

        $channel->shouldReceive('getParameters')->andReturn(['foo' => 'bar'])->atLeast()->once();
        $driver->shouldReceive('getDefaultParameters')->andReturn(['foo' => '', 'bar' => ''])->once();

        $driver = $driver->initialize($channel, $request);

        $this->assertSame('bar', $driver->getParameters()['foo']);
        $this->assertSame('', $driver->getParameters()['bar']);
    }

    private function createExtendDriver(Client $client = null) : Driver
    {
        return new class($client ?? new Client()) extends Driver {
            public function getName(): string
            {
                return $this->faker()->word;
            }

            public function getDefaultParameters(): array
            {
                return [];
            }
        };
    }
}
