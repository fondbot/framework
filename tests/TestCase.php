<?php

declare(strict_types=1);

namespace Tests;

use Mockery;
use FondBot\Bot;
use Faker\Factory;
use Faker\Generator;
use Psr\Log\LoggerInterface;
use Tests\Classes\TestContainer;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    /** @var TestContainer */
    protected $container;

    /** @var Mockery\Mock */
    protected $guzzle;

    /** @var Mockery\Mock */
    protected $filesystem;

    protected function setUp()
    {
        parent::setUp();

        // Set up container
        $this->container = new Classes\TestContainer();

        $logger = $this->mock(LoggerInterface::class);
        $logger->shouldReceive('debug', 'error');

        $bot = $this->mock(Bot::class);
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
