<?php

declare(strict_types=1);

namespace FondBot\Tests;

use Mockery;
use Faker\Factory;
use Faker\Generator;
use FondBot\Channels\Chat;
use FondBot\Channels\User;
use FondBot\Foundation\Kernel;
use FondBot\Conversation\Context;
use FondBot\Foundation\ServiceProvider;
use FondBot\Foundation\Providers\ChannelServiceProvider;
use FondBot\Foundation\Providers\ConversationServiceProvider;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    /** @var Mockery\Mock|mixed */
    protected $kernel;

    protected function setUp(): void
    {
        parent::setUp();

        $this->kernel = $this->mock(Kernel::class);
    }

    protected function getPackageProviders($app): array
    {
        return [
            ServiceProvider::class,
            ChannelServiceProvider::class,
            ConversationServiceProvider::class,
        ];
    }

    protected function setContext(Context $context)
    {
        $this->app->instance('fondbot.conversation.context', $context);

        return $this;
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

        $this->app->instance($class, $instance);

        return $instance;
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
}
