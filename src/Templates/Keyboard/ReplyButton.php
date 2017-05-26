<?php

declare(strict_types=1);

namespace FondBot\Templates\Keyboard;

class ReplyButton implements Button
{
    private $label;

    /**
     * Get label.
     *
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * Set label.
     *
     * @param string $label
     *
     * @return ReplyButton
     */
    public function setLabel(string $label): ReplyButton
    {
        $this->label = $label;

        return $this;
    }
}
