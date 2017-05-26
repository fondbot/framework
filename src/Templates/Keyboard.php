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
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'buttons' => $this->buttons,
        ];
    }

    /**
     * Specify data which should be serialized to JSON.
     * @link  http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
