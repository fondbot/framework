<?php

declare(strict_types=1);

namespace FondBot\Templates\Keyboard;

class UrlButton implements Button
{
    private $label;
    private $url;
    private $parameters;

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
     * @return UrlButton
     */
    public function setLabel(string $label): UrlButton
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get URL.
     *
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * Set URL.
     *
     * @param mixed $url
     *
     * @return UrlButton
     */
    public function setUrl($url): UrlButton
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get parameters.
     *
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * Set parameters.
     *
     * @param mixed $parameters
     *
     * @return UrlButton
     */
    public function setParameters($parameters): UrlButton
    {
        $this->parameters = $parameters;

        return $this;
    }
}
