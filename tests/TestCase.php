<?php

declare(strict_types=1);

namespace Tests;

use Mockery;
use FondBot\Bot;
use Faker\Factory;
use Faker\Generator;
use Tests\Classes\TestContainer;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    /** @var TestContainer */
    protected $container;

    protected function setUp()
    {
        parent::setUp();

        // Set up container
        $this->container = new Classes\TestContainer();

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
        return Mockery::mock($class);
    }
}
