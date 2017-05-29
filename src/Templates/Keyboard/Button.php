<?php

declare(strict_types=1);

namespace FondBot\Templates\Keyboard;

use FondBot\Contracts\Template;

interface Button extends Template
{
    /**
     * Get label.
     *
     * @return string
     */
    public function getLabel(): string;

    /**
     * Set label.
     *
     * @param string $label
     *
     * @return static
     */
    public function setLabel(string $label);
}
