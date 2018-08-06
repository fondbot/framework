<?php

declare(strict_types=1);

namespace FondBot\Templates\Keyboard;

class ReplyButton extends Button
{
    /**
     * Make a new reply button instance.
     *
     * @param string $label
     * @param array  $parameters
     *
     * @return static
     */
    public static function make(string $label, array $parameters = [])
    {
        return new static($label, $parameters);
    }
}
