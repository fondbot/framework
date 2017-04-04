<?php

declare(strict_types=1);

namespace Tests\Unit\Conversation\Traits;

use FondBot\Bot;
use Tests\TestCase;
use FondBot\Conversation\Intent;
use FondBot\Conversation\Interaction;
use FondBot\Conversation\Traits\Transitions;

class TransitionsTest extends TestCase
{
    public function test_jump()
    {
        $bot = $this->mock(Bot::class);

        $bot->shouldReceive('get')->with('foo')->andReturn($interaction = $this->mock(Interaction::class))->once();
        $bot->shouldReceive('converse')->with($interaction)->once();

        $class = new TransitionsTraitTestClass($bot);
        $class->jump('foo');
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid interaction `foo`
     */
    public function test_jump_invalid_interaction()
    {
        $bot = $this->mock(Bot::class);

        $bot->shouldReceive('get')->with('foo')->andReturn($this->mock(Intent::class))->once();
        $bot->shouldReceive('converse')->never();

        $class = new TransitionsTraitTestClass($bot);
        $class->jump('foo');
    }
}

class TransitionsTraitTestClass
{
    use Transitions;

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
