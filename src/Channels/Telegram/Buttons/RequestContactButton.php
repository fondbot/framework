<?php

declare(strict_types=1);

namespace FondBot\Channels\Telegram\Buttons;

use FondBot\Contracts\Conversation\Button;

class RequestContactButton implements Button
{
    private $label;

    public function __construct(string $label)
    {
        $this->label = $label;
    }

    /**
     * Button label.
     *
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }
}
