<?php

declare(strict_types=1);

namespace FondBot\Templates\Keyboard;

class UrlButton extends Button
{
    private $url;
    private $parameters;

    /**
     * Get name.
     *
     * @return string
     */
    public function getName(): string
    {
        return 'UrlButton';
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
