<?php

declare(strict_types=1);

namespace FondBot\Templates\Keyboard;

class UrlButton implements Button
{
    private $label;
    private $url;
    private $parameters;

    public function __construct(string $label, string $url, array $parameters = [])
    {
        $this->label = $label;
        $this->url = $url;
        $this->parameters = $parameters;
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
     * URL.
     *
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * Additional parameters.
     *
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }
}
