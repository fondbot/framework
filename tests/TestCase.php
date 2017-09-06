<?php

declare(strict_types=1);

namespace FondBot\Tests;

use Mockery;
use Carbon\Carbon;
use Faker\Factory;
use Faker\Generator;
use FondBot\Channels\Chat;
use FondBot\Channels\User;
use FondBot\Foundation\Kernel;
use Illuminate\Cache\ArrayStore;
use Illuminate\Cache\Repository;
use FondBot\Conversation\Context;
use FondBot\Conversation\Session;
use Illuminate\Container\Container;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Contracts\Bus\Dispatcher;
use League\Flysystem\Memory\MemoryAdapter;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Testing\Fakes\BusFake;
use Illuminate\Contracts\Filesystem\Filesystem;
use PHPUnit\Framework\TestCase as BaseTestCase;
use League\Flysystem\Filesystem as LeagueFilesystem;
use Illuminate\Contracts\Container\Container as ContainerContract;

abstract class TestCase extends BaseTestCase
{
    /** @var Container|mixed */
    protected $container;

    /** @var Mockery\MockInterface */
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
        $this->container = Container::setInstance(new Container);
        $this->kernel = $this->mock(Kernel::class);

        $this->container->instance(Kernel::class, $this->kernel);
        $this->container->instance(ContainerContract::class, $this->container);

        $this->container->instance(Dispatcher::class, new BusFake);
    }

    protected function setSession(Session $session)
    {
        $this->kernel->shouldReceive('getSession')->andReturn($session)->atLeast()->once();

        return $this;
    }

    protected function setContext(Context $context)
    {
        $this->kernel->shouldReceive('getContext')->andReturn($context)->atLeast()->once();

        return $this;
    }

    protected function cache(): Repository
    {
        if (!$this->container->bound(Store::class)) {
            $this->container->instance(Store::class, new ArrayStore);
        }

        return new Repository($this->container->make(Store::class));
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

    protected function fakeChat(): Chat
    {
        return new Chat($this->faker()->uuid, $this->faker()->word);
    }

    protected function fakeUser(): User
    {
        return new User($this->faker()->uuid, $this->faker()->name, $this->faker()->userName);
    }

    /**
     * @param string $class
     *
     * @param array  $args
     *
     * @return mixed|Mockery\Mock
     */
    protected function mock(string $class, array $args = null)
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
