<?php

declare(strict_types=1);

namespace FondBot\Drivers;

use FondBot\Contracts\Template;
use FondBot\Templates\Keyboard;

abstract class TemplateCompiler
{
    /**
     * Render keyboard.
     *
     * @param Keyboard $keyboard
     *
     * @return mixed
     */
    abstract protected function compileKeyboard(Keyboard $keyboard);

    /**
     * Compile template.
     *
     * @param Template $template
     *
     * @return mixed
     */
    public function compile(Template $template)
    {
        // Otherwise, we look for a compile method
        $method = 'compile'.ucfirst($template->getName());
        if (!method_exists($this, $method)) {
            return null;
        }

        return $this->$method($template);
    }
}
