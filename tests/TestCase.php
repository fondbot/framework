<?php

declare(strict_types=1);

namespace FondBot\Tests;

use Mockery;
use Faker\Factory;
use Faker\Generator;
use FondBot\Application\Kernel;
use League\Container\Container;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    /** @var Container */
    protected $container;

    /** @var Kernel|Mockery\Mock|mixed */
    protected $kernel;

    protected function setUp(): void
    {
        parent::setUp();
        Mockery::getConfiguration()->allowMockingNonExistentMethods(false);

        $this->container = new Container;
        $this->kernel = Kernel::createInstance($this->container);
        $this->container->add(Kernel::class, $this->kernel);
    }

    protected function tearDown(): void
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
