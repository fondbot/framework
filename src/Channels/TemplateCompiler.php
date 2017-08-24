<?php

declare(strict_types=1);

namespace FondBot\Channels;

use RuntimeException;
use FondBot\Contracts\Template;
use FondBot\Templates\Keyboard;
use FondBot\Templates\Keyboard\UrlButton;
use FondBot\Templates\Keyboard\ReplyButton;
use Illuminate\Contracts\Support\Arrayable;
use FondBot\Templates\Keyboard\PayloadButton;

abstract class TemplateCompiler
{
    /**
     * Compile keyboard.
     *
     * @param Keyboard $keyboard
     * @param array    $args
     *
     * @return mixed
     */
    abstract protected function compileKeyboard(Keyboard $keyboard, array $args);

    /**
     * Compile payload button.
     *
     * @param PayloadButton $button
     * @param array         $args
     *
     * @return mixed
     */
    abstract protected function compilePayloadButton(PayloadButton $button, array $args);

    /**
     * Compile reply button.
     *
     * @param ReplyButton $button
     * @param array       $args
     *
     * @return mixed
     */
    abstract protected function compileReplyButton(ReplyButton $button, array $args);

    /**
     * Compile url button.
     *
     * @param UrlButton $button
     * @param array     $args
     *
     * @return mixed
     */
    abstract protected function compileUrlButton(UrlButton $button, array $args);

    /**
     * Compile template.
     *
     * @param Template $template
     * @param array    $args
     *
     * @return mixed
     */
    public function compile(Template $template, array $args = [])
    {
        // If template can compile itself we recursively compile subelements
        if ($template instanceof Arrayable) {
            $array = $template->toArray();
            $transformer = function ($value) use (&$transformer, $args) {
                if (is_array($value)) {
                    return array_map($transformer, $value);
                }

                if ($value instanceof Arrayable) {
                    return array_map($transformer, $value->toArray());
                }

                if ($value instanceof Template) {
                    return $this->compile($value, $args);
                }

                return $value;
            };

            return array_map($transformer, $array);
        }

        // Otherwise, we look for a compile method
        $method = 'compile'.ucfirst($template->getName());
        if (!method_exists($this, $method)) {
            throw new RuntimeException('No compile method for "'.$template->getName().'".');
        }

        return $this->$method($template, $args);
    }
}
