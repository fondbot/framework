<?php

declare(strict_types=1);

namespace FondBot\Templates\Keyboard;

class UrlButton extends Button
{
    private $url;

    public function __construct(string $label, string $url, array $parameters = [])
    {
        parent::__construct($label, $parameters);

        $this->url = $url;
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
}
