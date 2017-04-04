<?php

declare(strict_types=1);

namespace FondBot\Conversation\Activators;

use Spatie\Regex\Regex;
use FondBot\Contracts\Conversation\Activator;
use FondBot\Contracts\Drivers\ReceivedMessage;
use VerbalExpressions\PHPVerbalExpressions\VerbalExpressions;

class Pattern implements Activator
{
    private $value;

    /**
     * Pattern constructor.
     *
     * @param string|VerbalExpressions $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * Result of matching activator.
     *
     * @param ReceivedMessage $message
     *
     * @return bool
     */
    public function matches(ReceivedMessage $message): bool
    {
        $text = $message->getText();

        if ($this->value instanceof VerbalExpressions) {
            return $this->value->test($text);
        }

        return Regex::match($this->value, $text)->hasMatch();
    }
}
