<?php

declare(strict_types=1);

namespace Tests\Unit\Conversation\Traits;

use FondBot\Bot;
use Tests\TestCase;
use FondBot\Drivers\User;
use FondBot\Conversation\Context;
use FondBot\Conversation\Traits\InteractsWithContext;

class InteractsWithContextTest extends TestCase
{
    public function test_remember()
    {
        $bot = $this->mock(Bot::class);
        $context = $this->mock(Context::class);

        $bot->shouldReceive('getContext')->andReturn($context)->once();
        $context->shouldReceive('setValue')->with('foo', 'bar')->once();

        $class = new InteractsWithContextTraitTestClass($bot);
        $class->remember('foo', 'bar');
    }

    public function test_user()
    {
        $bot = $this->mock(Bot::class);
        $context = $this->mock(Context::class);
        $user = $this->mock(User::class);

        $bot->shouldReceive('getContext')->andReturn($context)->once();
        $context->shouldReceive('getUser')->andReturn($user)->once();

        $class = new InteractsWithContextTraitTestClass($bot);
        $this->assertSame($user, $class->user());
    }
}

class InteractsWithContextTraitTestClass
{
    use InteractsWithContext;

    protected $bot;

    public function __construct(Bot $bot)
    {
        $this->bot = $bot;
    }

    public function __call($name, $arguments)
    {
        return $this->$name(...$arguments);
    }
}
