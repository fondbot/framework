<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Channels;

use GuzzleHttp\Client;
use FondBot\Tests\TestCase;
use FondBot\Tests\Mocks\FakeDriver;

class DriverTest extends TestCase
{
    /**
     * @var Client
     */
    protected $guzzle;

    public function setUp() : void
    {
        parent::setUp();
        $this->guzzle = $this->mock(Client::class);
    }

    public function testInitialize(): void
    {
        $driver = new FakeDriver;
        $parameters = collect(['foo' => 'bar']);

        $this->assertSame('', $driver->getParameters()->get('foo'));

        $driver = $driver->initialize($parameters);

        $this->assertSame('bar', $driver->getParameters()->get('foo'));
    }

    public function testGetShortName() : void
    {
        $driver = new FakeDriver;
        $this->assertSame('FakeDriver', $driver->getShortName());
    }
}
