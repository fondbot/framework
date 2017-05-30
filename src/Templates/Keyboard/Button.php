<?php

declare(strict_types=1);

namespace FondBot\Templates\Keyboard;

use FondBot\Contracts\Template;

abstract class Button implements Template
{
    protected $label;

    /**
     * Get label.
     *
     * @return string
     */
    public function getLabel(): ?string
    {
        return $this->label;
    }

    /**
     * Set label.
     *
     * @param string $label
     *
     * @return static
     */
    public function setLabel(string $label)
    {
        $this->label = $label;

        return $this;
    }
}
