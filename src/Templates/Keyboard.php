<?php

declare(strict_types=1);

namespace FondBot\Templates;

use FondBot\Contracts\Template;
use FondBot\Templates\Keyboard\Button;

class Keyboard implements Template
{
    /** @var Button[] */
    private $buttons;
    private $parameters;

    /**
     * @param Button[] $buttons
     * @param array    $parameters
     */
    public function __construct(array $buttons = [], array $parameters = [])
    {
        $this->buttons = $buttons;
        $this->parameters = $parameters;
    }

    /**
     * @param Button[] $buttons
     * @param array    $parameters
     *
     * @return static
     */
    public static function create(array $buttons = [], array $parameters = [])
    {
        return new static($buttons, $parameters);
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName(): string
    {
        return 'Keyboard';
    }

    /**
     * Get buttons.
     *
     * @return Button[]
     */
    public function getButtons(): array
    {
        return $this->buttons;
    }

    /**
     * Add button.
     *
     * @param Button $button
     *
     * @return Keyboard
     */
    public function addButton(Button $button): Keyboard
    {
        $this->buttons[] = $button;

        return $this;
    }

    /**
     * Get keyboard parameters.
     *
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * Set keyboard parameters.
     *
     * @param array $parameters
     *
     * @return Keyboard
     */
    public function setParameters(array $parameters): Keyboard
    {
        $this->parameters = $parameters;

        return $this;
    }
}
