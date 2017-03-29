<?php

declare(strict_types=1);

namespace Tests;

use Mockery;
use FondBot\Bot;
use Faker\Factory;
use Faker\Generator;
use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;
use Tests\Classes\FakeContainer;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    /** @var FakeContainer */
    protected $container;

    /** @var Mockery\Mock */
    protected $guzzle;

    protected function setUp()
    {
        parent::setUp();

        // Set up container
        $this->container = new Classes\FakeContainer();

        $logger = $this->mock(LoggerInterface::class);
        $logger->shouldReceive('debug', 'error');

        $this->guzzle = $this->mock(Client::class);

        $bot = $this->mock(Bot::class);
        $bot->shouldReceive('get')->with(LoggerInterface::class)->andReturn($this->container->make(LoggerInterface::class));
        $bot->shouldReceive('get')->with(Client::class)->andReturn($this->container->make(Client::class));
        Bot::setInstance($bot);
    }

    protected function tearDown()
    {
        parent::tearDown();

        Mockery::close();
    }

    protected function faker(): Generator
    {
        return Factory::create();
    }

    /**
     * @param string $class
     *
     * @return \Mockery\Mock|mixed
     */
    protected function mock(string $class)
    {
        $instance = Mockery::mock($class);

        $this->container->singleton($class, $instance);

        return $instance;
    }

    protected function spy(string $class)
    {
        $instance = Mockery::spy($class)->makePartial();

        $this->container->singleton($class, $instance);

        return $instance;
    }
}
