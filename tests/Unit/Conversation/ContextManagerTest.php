<?php
declare(strict_types=1);

namespace FondBot\Tests\Unit\Conversation;

use FondBot\Conversation\Context;
use FondBot\Conversation\ContextManager;
use FondBot\Drivers\Chat;
use FondBot\Drivers\User;
use FondBot\Tests\TestCase;
use FondBot\Channels\Channel;
use Psr\SimpleCache\CacheInterface;
use FondBot\Drivers\Driver;

class ContextManagerTest extends TestCase
{
    /**
     * @var CacheInterface
     */
    protected $cache;
    /**
     * @var ContextManager
     */
    protected $contextManager;

    /**
     * @var string
     */
    protected $loadString;

    /**
     * @var Channel
     */
    protected $channel;

    /**
     * @var Context
     */
    protected $context;

    /**
     * @var Chat
     */
    protected $chat;

    /**
     * @var User
     */
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->cache = $this->mock(CacheInterface::class);
        $this->contextManager = new ContextManager($this->cache);
        $this->loadString = $this->faker()->randomLetter;
        $this->channel = $this->mock(Channel::class);
        $this->context = $this->mock(Context::class);
        $this->chat = $this->mock(Chat::class);
        $this->user = $this->mock(User::class);
    }

    public function test_load() : void
    {
        $driver  = $this->mock(Driver::class);

        $driver->shouldReceive('getChat')->once()->andReturn($this->chat);
        $driver->shouldReceive('getUser')->once()->andReturn($this->user);
        $this->cache->shouldReceive('get')->once()->andReturn(
            ['chat' => $this->channel, 'user' => $this->chat, 'items' => $this->user]
        );
        $this->chat->shouldReceive('getId')->once()->andReturn($this->loadString);
        $this->user->shouldReceive('getId')->once()->andReturn($this->loadString);
        $this->channel->shouldReceive('getName')->once()->andReturn($this->loadString);
        $this->contextManager->load($this->channel, $driver);
    }

    public function test_save() : void
    {
        $this->context->shouldReceive('getChannel')->once()->andReturn($this->channel);
        $this->context->shouldReceive('getChat')->once()->andReturn($this->chat);
        $this->context->shouldReceive('getUser')->once()->andReturn($this->user);
        $this->context->shouldReceive('toArray')->once();
        $this->chat->shouldReceive('getId')->once()->andReturn($this->loadString);
        $this->user->shouldReceive('getId')->once()->andReturn($this->loadString);
        $this->channel->shouldReceive('getName')->once()->andReturn($this->loadString);

        $this->cache->shouldReceive('set')->once();
        $this->contextManager->save($this->context);
    }

    public function test_clear()
    {
        $this->context->shouldReceive('getChannel')->once()->andReturn($this->channel);
        $this->context->shouldReceive('getChat')->once()->andReturn($this->chat);
        $this->context->shouldReceive('getUser')->once()->andReturn($this->user);
        $this->chat->shouldReceive('getId')->once()->andReturn($this->loadString);
        $this->user->shouldReceive('getId')->once()->andReturn($this->loadString);
        $this->channel->shouldReceive('getName')->once()->andReturn($this->loadString);
        $this->cache->shouldReceive('delete')->once();
        $this->contextManager->clear($this->context);
    }
}