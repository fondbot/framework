<?php

declare(strict_types=1);

namespace Tests\Unit\Conversation\Activators;

use Tests\TestCase;
use FondBot\Conversation\Activators\Pattern;
use Tests\Classes\Fakes\FakeReceivedMessage;
use VerbalExpressions\PHPVerbalExpressions\VerbalExpressions;

class PatternTest extends TestCase
{
    public function test_string_matches()
    {
        $activator = new Pattern('/abc/');
        $this->assertTrue(
            $activator->matches(new FakeReceivedMessage(null, 'abc'))
        );
    }

    public function test_string_does_not_match()
    {
        $activator = new Pattern('/abc/');
        $this->assertFalse(
            $activator->matches(new FakeReceivedMessage(null, 'ab'))
        );
    }

    public function test_verbal_expression_matches()
    {
        $expression = new VerbalExpressions();
        $expression
            ->startOfLine()
            ->then('https://')
            ->anything()
            ->endOfLine();

        $activator = new Pattern($expression);
        $this->assertTrue(
            $activator->matches(new FakeReceivedMessage(null, 'https://fondbot.com'))
        );
    }

    public function test_verbal_expression_does_not_match()
    {
        $expression = new VerbalExpressions();
        $expression
            ->startOfLine()
            ->then('https://')
            ->anything()
            ->endOfLine();

        $activator = new Pattern($expression);
        $this->assertFalse(
            $activator->matches(new FakeReceivedMessage(null, 'http://fondbot.com'))
        );
    }
}
