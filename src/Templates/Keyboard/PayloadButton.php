<?php

declare(strict_types=1);

namespace FondBot\Templates\Keyboard;

class PayloadButton extends Button
{
    private $payload;

    public function __construct(string $label, $payload, array $parameters = [])
    {
        parent::__construct($label, $parameters);

        $this->payload = $payload;
    }

    /**
     * Make a new payload button instance.
     *
     * @param string $label
     * @param mixed  $payload
     * @param array  $parameters
     *
     * @return static
     */
    public static function make(string $label, $payload, array $parameters = [])
    {
        return new static($label, $payload, $parameters);
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
