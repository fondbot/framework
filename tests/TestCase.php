<?php

declare(strict_types=1);

namespace FondBot\Tests;

use Mockery;
use Faker\Factory;
use Faker\Generator;
use FondBot\FondBot;
use FondBot\Channels\Chat;
use FondBot\Channels\User;
use FondBot\Conversation\Context;
use FondBot\FondBotServiceProvider;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    /** @var Mockery\Mock|mixed */
    protected $kernel;

    protected function setUp(): void
    {
        parent::setUp();

        $this->kernel = $this->mock(FondBot::class);
    }

    protected function getPackageProviders($app): array
    {
        return [
            FondBotServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('fondbot', [
            'channels' => [],
            'intents_path' => [__DIR__.'/Mocks'],
            'fallback_intent' => \FondBot\Conversation\FallbackIntent::class,
            'context_ttl' => 10,
        ]);
    }

    protected function setContext(Context $context)
    {
        $this->app->instance('fondbot.conversation.context', $context);

        return $this;
    }

    /**
     * @param string $class
     *
     * @param array $args
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
