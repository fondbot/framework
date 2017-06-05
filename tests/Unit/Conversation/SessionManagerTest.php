<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Conversation;

use FondBot\Drivers\Chat;
use FondBot\Drivers\User;
use FondBot\Drivers\Driver;
use FondBot\Tests\TestCase;
use FondBot\Contracts\Cache;
use FondBot\Channels\Channel;
use FondBot\Conversation\Session;
use FondBot\Drivers\ReceivedMessage;
use FondBot\Conversation\SessionManager;

/**
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface $channel
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface $chat
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface $sender
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface $receivedMessage
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface $driver
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface $cache
 * @property SessionManager                             $manager
 */
class SessionManagerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->channel = $this->mock(Channel::class);
        $this->chat = $this->mock(Chat::class);
        $this->sender = $this->mock(User::class);
        $this->receivedMessage = $this->mock(ReceivedMessage::class);
        $this->driver = $this->mock(Driver::class);
        $this->cache = $this->mock(Cache::class);

        $this->manager = new SessionManager($this->container, $this->cache);
    }

    public function test_load(): void
    {
        $this->driver->shouldReceive('getChat')->andReturn($this->chat)->once();
        $this->driver->shouldReceive('getUser')->andReturn($this->sender)->once();
        $this->driver->shouldReceive('getMessage')->andReturn($this->receivedMessage)->once();
        $this->chat->shouldReceive('getId')->andReturn($chatId = $this->faker()->uuid)->atLeast()->once();
        $this->sender->shouldReceive('getId')->andReturn($senderId = $this->faker()->uuid)->atLeast()->once();

        $key = 'session.foo.'.$chatId.'.'.$senderId;

        $this->channel->shouldReceive('getName')->andReturn('foo')->once();
        $this->cache->shouldReceive('get')->with($key)->andReturn([
            'intent' => null,
            'interaction' => null,
            'values' => $values = [
                'username' => $this->faker()->userName,
                'uuid' => $this->faker()->uuid,
            ],
        ])->once();

        $session = $this->manager->load($this->channel, $this->driver);

        $this->assertInstanceOf(Session::class, $session);
    }

    public function test_save(): void
    {
        $sessionArray = [
            'intent' => null,
            'interaction' => null,
            'values' => ['key1' => 'value1'],
        ];

        $session = $this->mock(Session::class);
        $session->shouldReceive('getChannel')->andReturn($this->channel)->atLeast()->once();
        $session->shouldReceive('getChat')->andReturn($this->chat)->atLeast()->once();
        $session->shouldReceive('getUser')->andReturn($this->sender)->atLeast()->once();
        $session->shouldReceive('toArray')->andReturn($sessionArray)->atLeast()->once();
        $this->channel->shouldReceive('getName')->andReturn('foo')->once();
        $this->chat->shouldReceive('getId')->andReturn($chatId = $this->faker()->uuid)->atLeast()->once();
        $this->sender->shouldReceive('getId')->andReturn($senderId = $this->faker()->uuid)->atLeast()->once();

        $key = 'session.foo.'.$chatId.'.'.$senderId;

        $this->cache->shouldReceive('store')->with($key, $sessionArray)->once();

        $this->manager->save($session);
    }

    public function test_close(): void
    {
        $session = $this->mock(Session::class);
        $session->shouldReceive('getChannel')->andReturn($this->channel)->once();
        $session->shouldReceive('getChat')->andReturn($this->chat)->atLeast()->once();
        $session->shouldReceive('getUser')->andReturn($this->sender)->once();
        $this->channel->shouldReceive('getName')->andReturn('foo')->once();
        $this->chat->shouldReceive('getId')->andReturn($chatId = $this->faker()->uuid)->atLeast()->once();
        $this->sender->shouldReceive('getId')->andReturn($senderId = $this->faker()->uuid)->atLeast()->once();

        $key = 'session.foo.'.$chatId.'.'.$senderId;

        $this->cache->shouldReceive('forget')->with($key)->once();

        $this->manager->close($session);
    }
}
