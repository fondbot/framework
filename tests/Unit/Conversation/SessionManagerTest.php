<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Conversation;

use FondBot\Channels\Chat;
use FondBot\Channels\User;
use FondBot\Tests\TestCase;
use FondBot\Channels\Driver;
use FondBot\Channels\Channel;
use FondBot\Conversation\Intent;
use Illuminate\Cache\Repository;
use FondBot\Conversation\Session;
use FondBot\Conversation\Interaction;
use Illuminate\Support\Facades\Cache;
use FondBot\Conversation\SessionManager;

/**
 * @property mixed|\Mockery\Mock channel
 * @property mixed|\Mockery\Mock chat
 * @property mixed|\Mockery\Mock $user
 * @property mixed|\Mockery\Mock receivedMessage
 * @property mixed|\Mockery\Mock driver
 * @property SessionManager      manager
 */
class SessionManagerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->channel = $this->mock(Channel::class);
        $this->chat = $this->mock(Chat::class);
        $this->user = $this->mock(User::class);
        $this->driver = $this->mock(Driver::class);

        $this->manager = new SessionManager($this->app, resolve(Repository::class));
    }

    public function testLoad(): void
    {
        $this->chat->shouldReceive('getId')->andReturn($chatId = $this->faker()->uuid)->atLeast()->once();
        $this->user->shouldReceive('getId')->andReturn($senderId = $this->faker()->uuid)->atLeast()->once();

        $this->app->instance('foo-intent', $intent = $this->mock(Intent::class));
        $this->app->instance('bar-interaction', $interaction = $this->mock(Interaction::class));

        Cache::forever('session.foo.'.$chatId.'.'.$senderId, [
            'intent' => 'foo-intent',
            'interaction' => 'bar-interaction',
        ]);

        $this->channel->shouldReceive('getName')->andReturn('foo')->once();

        $session = $this->manager->load($this->channel, $this->chat, $this->user);

        $this->assertInstanceOf(Session::class, $session);
    }

    public function testSave(): void
    {
        $sessionArray = [
            'intent' => 'foo',
            'interaction' => 'bar',
        ];

        $session = $this->mock(Session::class);
        $session->shouldReceive('getChannel')->andReturn($this->channel)->atLeast()->once();
        $session->shouldReceive('getChat')->andReturn($this->chat)->atLeast()->once();
        $session->shouldReceive('getUser')->andReturn($this->user)->atLeast()->once();
        $session->shouldReceive('toArray')->andReturn($sessionArray)->atLeast()->once();

        $this->channel->shouldReceive('getName')->andReturn('foo')->once();
        $this->chat->shouldReceive('getId')->andReturn($chatId = $this->faker()->uuid)->atLeast()->once();
        $this->user->shouldReceive('getId')->andReturn($senderId = $this->faker()->uuid)->atLeast()->once();

        $this->manager->save($session);

        $this->assertSame($sessionArray, Cache::get('session.foo.'.$chatId.'.'.$senderId));
    }

    public function testClose(): void
    {
        $session = $this->mock(Session::class);
        $session->shouldReceive('getChannel')->andReturn($this->channel)->once();
        $session->shouldReceive('getChat')->andReturn($this->chat)->atLeast()->once();
        $session->shouldReceive('getUser')->andReturn($this->user)->once();
        $this->channel->shouldReceive('getName')->andReturn('foo')->once();
        $this->chat->shouldReceive('getId')->andReturn($chatId = $this->faker()->uuid)->atLeast()->once();
        $this->user->shouldReceive('getId')->andReturn($senderId = $this->faker()->uuid)->atLeast()->once();

        $key = 'session.foo.'.$chatId.'.'.$senderId;

        Cache::forever($key, 'foo');

        $this->manager->close($session);

        $this->assertNull(Cache::get($key));
    }
}
