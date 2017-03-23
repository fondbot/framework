<?php

declare(strict_types=1);

namespace FondBot\Conversation\Keyboards;

use FondBot\Contracts\Conversation\Keyboard;

class BasicKeyboard implements Keyboard
{
    private $buttons;

    public function __construct(array $buttons)
    {
        $this->buttons = $buttons;
    }

    /**
     * Get keyboard type.
     *
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE_BASIC;
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
