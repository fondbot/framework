<?php

declare(strict_types=1);

namespace FondBot\Templates;

use FondBot\Conversation\Template;
use FondBot\Templates\Keyboard\Button;

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
    public function jsonSerialize(): string
    {
        return json_encode($this->toArray());
    }
}
