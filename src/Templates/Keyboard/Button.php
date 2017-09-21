<?php

declare(strict_types=1);

namespace FondBot\Templates\Keyboard;

use FondBot\Contracts\Template;
use Illuminate\Support\Collection;

abstract class Button implements Template
{
    protected $label;
    protected $parameters;

    public function __construct(string $label, array $parameters = [])
    {
        $this->label = $label;
        $this->parameters = collect($parameters);
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
    public function setLabel(string $label): Button
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get button parameters.
     *
     * @return Collection
     */
    public function getParameters(): Collection
    {
        return $this->parameters;
    }

    /**
     * Set parameters.
     *
     * @param array $parameters
     *
     * @return static
     */
    public function setParameters(array $parameters): Button
    {
        $this->parameters = $parameters;

        return $this;
    }
}
