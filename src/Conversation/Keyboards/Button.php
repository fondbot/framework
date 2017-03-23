<?php

declare(strict_types=1);

namespace FondBot\Conversation\Keyboards;

use FondBot\Contracts\Conversation\Button as ButtonContract;

class Button implements ButtonContract
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
