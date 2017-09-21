<?php

declare(strict_types=1);

namespace FondBot\Templates\Keyboard;

use FondBot\Contracts\Template;

abstract class Button implements Template
{
    protected $label;

    public function __construct(string $label)
    {
        $this->label = $label;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName(): string
    {
        return class_basename($this);
    }

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
