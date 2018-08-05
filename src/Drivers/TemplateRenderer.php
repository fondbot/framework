<?php

declare(strict_types=1);

namespace FondBot\Drivers;

use FondBot\Contracts\Template;
use FondBot\Templates\Keyboard;

abstract class TemplateRenderer
{
    /**
     * Render keyboard.
     *
     * @param Keyboard $keyboard
     *
     * @return Type|null
     */
    abstract protected function renderKeyboard(Keyboard $keyboard): ?Type;

    /**
     * Compile template.
     *
     * @param Template $template
     *
     * @return Type|mixed|null
     */
    public function render(Template $template)
    {
        // Otherwise, we look for a compile method
        $method = 'render'.ucfirst($template->getName());
        if (!method_exists($this, $method)) {
            return null;
        }

        /** @var Type $type */
        $type = $this->$method($template);

        return $type->toNative() ?? $type;
    }
}
