<?php

declare(strict_types=1);

namespace FondBot\Templates\Keyboard;

class ReplyButton extends Button
{
    /**
     * Create a new reply button instance.
     *
     * @param string $label
     * @param array  $parameters
     *
     * @return static
     */
    public static function create(string $label, array $parameters = [])
    {
        return new static($label, $parameters);
    }
}
