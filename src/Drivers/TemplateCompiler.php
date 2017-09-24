<?php

declare(strict_types=1);

namespace FondBot\Drivers;

use FondBot\Contracts\Template;
use FondBot\Templates\Keyboard;

abstract class TemplateCompiler
{
    /**
     * Compile keyboard.
     *
     * @param Keyboard $keyboard
     *
     * @return Type|null
     */
    abstract protected function compileKeyboard(Keyboard $keyboard): ?Type;

    /**
     * Compile template.
     *
     * @param Template $template
     *
     * @return Type|null
     */
    public function compile(Template $template): ?Type
    {
        // Otherwise, we look for a compile method
        $method = 'compile'.ucfirst($template->getName());
        if (!method_exists($this, $method)) {
            return null;
        }

        return $this->$method($template);
    }
}
