<?php

declare(strict_types=1);

namespace FondBot\Tests;

use Mockery;
use Carbon\Carbon;
use Faker\Factory;
use Faker\Generator;
use FondBot\Foundation\Kernel;
use Illuminate\Bus\Dispatcher;
use Illuminate\Cache\ArrayStore;
use Illuminate\Container\Container;
use Illuminate\Contracts\Cache\Store;
use League\Flysystem\Memory\MemoryAdapter;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Testing\Fakes\BusFake;
use Illuminate\Contracts\Filesystem\Filesystem;
use League\Flysystem\Filesystem as LeagueFilesystem;
use Illuminate\Contracts\Container\Container as ContainerContract;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    /** @var Container */
    protected $container;

    /** @var Kernel */
    protected $kernel;

    protected function setUp(): void
    {
        parent::setUp();
        Mockery::getConfiguration()->allowMockingNonExistentMethods(false);
        Carbon::setTestNow(Carbon::now());

        $this->createApplication();
    }

    private function createApplication(): void
    {
        $this->container = new Container;
        $this->kernel = Kernel::createInstance($this->container, false);

        $this->container->instance(Kernel::class, $this->kernel);
        $this->container->instance(ContainerContract::class, $this->container);

        $this->container->instance(Dispatcher::class, new BusFake);
    }

    protected function cache(): Store
    {
        if (!$this->container->bound(Store::class)) {
            $this->container->instance(Store::class, new ArrayStore);
        }

        return $this->container->make(Store::class);
    }

    protected function filesystem(): FilesystemAdapter
    {
        if (!$this->container->bound(Filesystem::class)) {
            $adapter = new FilesystemAdapter(
                new LeagueFilesystem(
                    new MemoryAdapter
                )
            );

            $this->container->instance(Filesystem::class, $adapter);
        }

        return $this->container->make(Filesystem::class);
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
     * @param array  $args
     *
     * @return mixed|Mockery\Mock
     */
    protected function mock($class, array $args = null)
    {
        if ($args !== null) {
            $instance = Mockery::mock($class, $args);
        } else {
            $instance = Mockery::mock($class);
        }

        $this->container->instance($class, $instance);

        return $instance;
    }
}
