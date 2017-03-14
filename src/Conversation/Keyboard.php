<?php
declare(strict_types=1);

namespace FondBot\Conversation;

use FondBot\Conversation\Keyboards\Button;

abstract class Keyboard
{
    const TYPE_REPLY = 'reply';

    /** @var string */
    protected $type;

    /** @var array */
    protected $buttons = [];

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return Button[]|array
     */
    public function getButtons(): array
    {
        return $this->buttons;
    }

    public function setButtons(array $buttons): void
    {
        $this->buttons = $buttons;
    }
}
