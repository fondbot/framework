<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Conversation;

use FondBot\Channels\Chat;
use FondBot\Channels\User;
use FondBot\Tests\TestCase;
use FondBot\Channels\Channel;
use FondBot\Conversation\Context;
use FondBot\Events\MessageReceived;
use FondBot\Tests\Mocks\FakeDriver;
use FondBot\Tests\Mocks\FakeIntent;
use FondBot\Conversation\FallbackIntent;
use FondBot\Tests\Mocks\FakeInteraction;
use FondBot\Conversation\ConversationManager;

class ConversationManagerTest extends TestCase
{
    /** @var ConversationManager */
    private $manager;

    protected function setUp(): void
    {
        parent::setUp();

        $this->manager = resolve(ConversationManager::class);
    }

    public function testRegisterIntent(): void
    {
        $this->manager->registerIntent('foo');
        $this->manager->registerIntent('bar');

        $this->assertAttributeEquals(['foo', 'bar'], 'intents', $this->manager);

        $this->assertSame(['foo', 'bar'], $this->manager->getIntents());
    }

    public function testRegisterFallbackIntent(): void
    {
        $this->manager->registerFallbackIntent('foo');

        $this->assertAttributeEquals('foo', 'fallbackIntent', $this->manager);
    }

    public function testMatchIntent(): void
    {
        $this->manager->registerIntent(FakeIntent::class);
        $this->manager->registerFallbackIntent(FallbackIntent::class);

        $messageReceived = new MessageReceived(Chat::create('1'), User::create('2'), 'foo');

        $result = $this->manager->matchIntent($messageReceived);

        $this->assertInstanceOf(FakeIntent::class, $result);

        $messageReceived = new MessageReceived(Chat::create('1'), User::create('2'), 'bar');

        $result = $this->manager->matchIntent($messageReceived);

        $this->assertInstanceOf(FallbackIntent::class, $result);
    }

    public function testResolveContext(): void
    {
        $key = 'context.foo-channel.foo-chat.foo-user';

        cache([$key => [
            'intent' => FakeIntent::class,
            'interaction' => FakeInteraction::class,
            'items' => ['foo' => 'bar'],
        ]], 100);

        $result = $this->manager->resolveContext(
            new Channel('foo-channel', new FakeDriver),
            Chat::create('foo-chat'),
            User::create('foo-user')
        );

        $this->assertSame('foo-channel', $result->getChannel()->getName());
        $this->assertSame('foo-chat', $result->getChat()->getId());
        $this->assertSame('foo-user', $result->getUser()->getId());
        $this->assertInstanceOf(FakeIntent::class, $result->getIntent());
        $this->assertInstanceOf(FakeInteraction::class, $result->getInteraction());
        $this->assertSame('bar', $result->get('foo'));
        $this->assertTrue($this->app->has('fondbot.conversation.context'));
        $this->assertSame($result, resolve('fondbot.conversation.context'));
    }

    public function testSaveContext(): void
    {
        $key = 'context.foo-channel.foo-chat.foo-user';

        $context = new Context(
            new Channel('foo-channel', new FakeDriver),
            Chat::create('foo-chat'),
            User::create('foo-user'),
            ['foo' => 'bar']
        );
        $context->setIntent(new FakeIntent)->setInteraction(new FakeInteraction);

        $this->manager->saveContext($context);

        $this->assertTrue(cache()->has($key));
        $this->assertSame(FakeIntent::class, array_get(cache($key), 'intent'));
        $this->assertSame(FakeInteraction::class, array_get(cache($key), 'interaction'));
        $this->assertSame(['foo' => 'bar'], array_get(cache($key), 'items'));
    }
}
