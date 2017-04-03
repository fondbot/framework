<?php

declare(strict_types=1);

namespace FondBot\Conversation\Buttons;

use FondBot\Contracts\Conversation\Button;

class UrlButton implements Button
{
    private $label;
    private $url;

    public function __construct(string $label, string $url)
    {
        $this->label = $label;
        $this->url = $url;
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
}
