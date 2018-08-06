<?php

declare(strict_types=1);

namespace FondBot\Templates;

use FondBot\Contracts\Template;
use Illuminate\Support\Collection;
use FondBot\Templates\Keyboard\Button;

class Keyboard implements Template
{
    /** @var Button[] */
    private $buttons;
    private $parameters;

    /**
     * @param Button[] $buttons
     * @param array    $parameters
     */
    public function __construct(array $buttons = [], array $parameters = [])
    {
        $this->buttons = $buttons;
        $this->parameters = collect($parameters);
    }

    /**
     * @param Button[] $buttons
     * @param array    $parameters
     *
     * @return static
     */
    public static function make(array $buttons = [], array $parameters = [])
    {
        return new static($buttons, $parameters);
    }

    public function getName(): string
    {
        return 'Keyboard';
    }

    /**
     * @return Button[]
     */
    public function getButtons(): array
    {
        return $this->buttons;
    }

    public function addButton(Button $button): Keyboard
    {
        $this->buttons[] = $button;

        return $this;
    }

    public function getParameters(): Collection
    {
        return $this->parameters;
    }

    public function setParameters(array $parameters): Keyboard
    {
        $this->parameters = $parameters;

        return $this;
    }
}
