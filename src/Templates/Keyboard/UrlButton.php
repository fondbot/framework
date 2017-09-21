<?php

declare(strict_types=1);

namespace FondBot\Templates\Keyboard;

class UrlButton extends Button
{
    private $url;
    private $parameters;

    public function __construct(string $label, string $url, array $parameters = [])
    {
        parent::__construct($label);

        $this->url = $url;
        $this->parameters = $parameters;
    }

    public static function create(string $label, string $url, array $parameters = [])
    {
        return new static($label, $url, $parameters);
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
    public function setParameters(array $parameters): UrlButton
    {
        $this->parameters = $parameters;

        return $this;
    }
}
