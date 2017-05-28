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
    /**
     * Compile keyboard.
     *
     * @param Keyboard $keyboard
     *
     * @return mixed
     */
    abstract public function compileKeyboard(Keyboard $keyboard);

    /**
     * Compile payload button.
     *
     * @param PayloadButton $button
     *
     * @return mixed
     */
    abstract public function compilePayloadButton(PayloadButton $button);

    /**
     * Compile reply button.
     *
     * @param ReplyButton $button
     *
     * @return mixed
     */
    abstract public function compileReplyButton(ReplyButton $button);

    /**
     * Compile url button.
     *
     * @param UrlButton $button
     *
     * @return mixed
     */
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
