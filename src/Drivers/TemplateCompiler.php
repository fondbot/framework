<?php

declare(strict_types=1);

namespace FondBot\Drivers;

use RuntimeException;
use FondBot\Contracts\Template;
use FondBot\Templates\Keyboard;
use FondBot\Templates\Keyboard\UrlButton;
use FondBot\Templates\Keyboard\ReplyButton;
use FondBot\Templates\Keyboard\PayloadButton;

abstract class TemplateCompiler
{
    abstract public function compileKeyboard(Keyboard $keyboard);

    abstract public function compilePayloadButton(PayloadButton $button);

    abstract public function compileReplyButton(ReplyButton $button);

    abstract public function compileUrlButton(UrlButton $button);

    /**
     * Compile template.
     *
     * @param Template $template
     *
     * @return mixed
     *
     * @throws RuntimeException
     */
    public function compile(Template $template)
    {
        $method = 'compile'.ucfirst($template->getName());
        if (!method_exists($this, $method)) {
            throw new RuntimeException('No compile method for "'.$template->getName().'".');
        }

        return $this->$method($template);
    }
}
