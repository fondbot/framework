<?php

declare(strict_types=1);

namespace FondBot\Conversation\Buttons;

class ReplyButton implements Button
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
