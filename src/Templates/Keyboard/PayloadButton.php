<?php

declare(strict_types=1);

namespace FondBot\Templates\Keyboard;

class PayloadButton extends Button
{
    private $payload;

    /**
     * Get name.
     *
     * @return string
     */
    public function getName(): string
    {
        return 'PayloadButton';
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
