<?php

declare(strict_types=1);

namespace FondBot\Conversation\Templates\Keyboard;

interface Button
{
    /**
     * Button label.
     *
     * @return string
     */
    public function getLabel(): string;
}
