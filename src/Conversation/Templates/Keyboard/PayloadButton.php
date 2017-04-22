<?php

declare(strict_types=1);

namespace FondBot\Conversation\Templates\Keyboard;

class PayloadButton implements Button
{
    private $label;
    private $payload;

    public function __construct(string $label, $payload)
    {
        $this->label = $label;
        $this->payload = $payload;
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

    /**
     * @return mixed
     */
    public function getPayload()
    {
        return $this->payload;
    }
}
