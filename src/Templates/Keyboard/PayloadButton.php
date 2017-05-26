<?php

declare(strict_types=1);

namespace FondBot\Templates\Keyboard;

class PayloadButton implements Button
{
    private $label;
    private $payload;

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
     * @return PayloadButton
     */
    public function setLabel(string $label): PayloadButton
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get payload.
     *
     * @return mixed
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * Set payload.
     *
     * @param mixed $payload
     *
     * @return PayloadButton
     */
    public function setPayload($payload): PayloadButton
    {
        $this->payload = $payload;

        return $this;
    }
}
