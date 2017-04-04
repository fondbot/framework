<?php

declare(strict_types=1);

namespace FondBot\Conversation;

use FondBot\Conversation\Buttons\Button;

class Keyboard
{
    /** @var Button[] */
    private $buttons;

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
