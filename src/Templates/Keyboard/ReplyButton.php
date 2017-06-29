<?php

declare(strict_types=1);

namespace FondBot\Templates\Keyboard;

class ReplyButton extends Button
{
    /**
     * Get name.
     *
     * @return string
     */
    public function getName(): string
    {
        return 'ReplyButton';
    }
}
