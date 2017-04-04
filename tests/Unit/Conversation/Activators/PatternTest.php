<?php

declare(strict_types=1);

namespace Tests\Unit\Conversation\Activators;

use Tests\TestCase;
use FondBot\Drivers\ReceivedMessage;
use FondBot\Conversation\Activators\Pattern;
use VerbalExpressions\PHPVerbalExpressions\VerbalExpressions;

/**
 * @property mixed|\Mockery\Mock message
 */
class PatternTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->message = $this->mock(ReceivedMessage::class);
    }

    public function test_string_matches()
    {
        $this->message->shouldReceive('getText')->andReturn('abc');

        $activator = new Pattern('/abc/');
        $this->assertTrue(
            $activator->matches($this->message)
        );
    }

    public function test_string_does_not_match()
    {
        $this->message->shouldReceive('getText')->andReturn('ab');

        $activator = new Pattern('/abc/');
        $this->assertFalse(
            $activator->matches($this->message)
        );
    }

    public function test_verbal_expression_matches()
    {
        $this->message->shouldReceive('getText')->andReturn('https://fondbot.com');

        $expression = new VerbalExpressions();
        $expression
            ->startOfLine()
            ->then('https://')
            ->anything()
            ->endOfLine();

        $activator = new Pattern($expression);
        $this->assertTrue(
            $activator->matches($this->message)
        );
    }

    public function test_verbal_expression_does_not_match()
    {
        $this->message->shouldReceive('getText')->andReturn('http://fondbot.com');

        $expression = new VerbalExpressions();
        $expression
            ->startOfLine()
            ->then('https://')
            ->anything()
            ->endOfLine();

        $activator = new Pattern($expression);
        $this->assertFalse(
            $activator->matches($this->message)
        );
    }
}
