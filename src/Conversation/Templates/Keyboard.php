<?php

declare(strict_types=1);

namespace FondBot\Conversation\Templates;

use FondBot\Conversation\Template;
use FondBot\Conversation\Templates\Keyboard\Button;

class Keyboard implements Template
{
    /** @var Button[] */
    protected $buttons;

    public function __construct(array $buttons)
    {
        $this->buttons = $buttons;
    }

    /**
     * Get keyboard buttons.
     *
     * @return Button[]
     */
    public function getButtons(): array
    {
        return $this->buttons;
    }
}
