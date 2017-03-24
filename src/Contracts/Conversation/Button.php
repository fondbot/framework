<?php

declare(strict_types=1);

namespace FondBot\Contracts\Conversation;

interface Button
{
    /**
     * Button label.
     *
     * @return string
     */
    public function getLabel(): string;
}
