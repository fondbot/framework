<?php

declare(strict_types=1);

namespace FondBot\Tests;

use Mockery;
use Faker\Factory;
use Faker\Generator;
use FondBot\Application\Kernel;
use FondBot\Application\Container;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    /** @var Container */
    protected $container;

    protected function setUp()
    {
        parent::setUp();
        Mockery::getConfiguration()->allowMockingNonExistentMethods(false);

        // Set up container
        $this->container = new Container;

        $kernel = $this->mock(Kernel::class);
        Kernel::setInstance($kernel);
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
