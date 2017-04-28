<?php

declare(strict_types=1);

namespace FondBot\Templates\Keyboard;

interface Button
{
    /**
     * Button label.
     *
     * @return string
     */
    public function getLabel(): string;
}
