<?php

declare(strict_types=1);

namespace FondBot\Templates;

use FondBot\Contracts\Template;
use FondBot\Templates\Keyboard\Button;

class Keyboard implements Template
{
    /** @var Button[] */
    private $buttons;

    /**
     * @param Button[] $buttons
     */
    public function __construct(array $buttons = [])
    {
        $this->buttons = $buttons;
    }

    /**
     * @param Button[] $buttons
     *
     * @return static
     */
    public static function create(array $buttons = [])
    {
        return new static($buttons);
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
}
