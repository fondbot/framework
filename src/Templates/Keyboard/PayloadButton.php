<?php

declare(strict_types=1);

namespace FondBot\Templates\Keyboard;

class PayloadButton extends Button
{
    private $payload;

    public function __construct(string $label, $payload)
    {
        parent::__construct($label);

        $this->payload = $payload;
    }

    /**
     * Create a new payload button instance.
     *
     * @param string $label
     * @param $payload
     *
     * @return static
     */
    public static function create(string $label, $payload)
    {
        return new static($label, $payload);
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
