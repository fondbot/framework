<?php

declare(strict_types=1);

namespace FondBot\Drivers;

use RuntimeException;
use FondBot\Contracts\Template;
use FondBot\Templates\Keyboard;
use FondBot\Contracts\Arrayable;
use FondBot\Templates\Keyboard\UrlButton;
use FondBot\Templates\Keyboard\ReplyButton;
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
     * @param Template|Template[] $templates
     * @param array               $args
     *
     * @return mixed
     */
    public function compile($templates, array $args = [])
    {
        if ($templates instanceof Template) {
            $templates = [$templates];
        }

        $result = [];

        foreach ($templates as $template) {
            // If template can compile itself there is no need to create additional method
            if ($template instanceof Arrayable) {
                // Firstly, we compile the template
                // Then we go through elements and compiled remaining templates
                $result[] = collect($template->toArray())
                    ->map(function ($element) use ($args) {
                        if ($element instanceof Template) {
                            return $this->compile($element, $args);
                        }

                        return $element;
                    })
                    ->toArray();
            } else {
                // Otherwise, we look for a compile method
                $method = 'compile'.ucfirst($template->getName());
                if (!method_exists($this, $method)) {
                    throw new RuntimeException('No compile method for "'.$template->getName().'".');
                }

                $result[] = $this->$method($template, $args);
            }
        }

        if (count($result) === 1) {
            return $result[0];
        }

        return $result;
    }
}
