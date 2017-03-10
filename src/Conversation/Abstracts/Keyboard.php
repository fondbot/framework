<?php declare(strict_types=1);

namespace FondBot\Conversation\Abstracts;

use FondBot\Conversation\Keyboards\Button;

abstract class Keyboard
{

    const TYPE_REPLY = 'reply';

    /** @var string */
    protected $type;

    /** @var array */
    protected $buttons = [];

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function setButtons(array $buttons): void
    {
        $this->buttons = $buttons;
    }

    /**
     * @return Button[]|array
     */
    public function buttons(): array
    {
        return $this->buttons;
    }

}