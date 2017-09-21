<?php

declare(strict_types=1);

namespace FondBot\Templates\Keyboard;

class ReplyButton extends Button
{
    /**
     * Create a new reply button instance.
     *
     * @param string $label
     *
     * @return static
     */
    public static function create(string $label)
    {
        return new static($label);
    }
}
