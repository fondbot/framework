<?php

declare(strict_types=1);

namespace FondBot\Conversation\Traits;

use FondBot\Contracts\Template;
use FondBot\Templates\Attachment;
use Illuminate\Container\Container;
use Illuminate\Contracts\Bus\Dispatcher;
use FondBot\Foundation\Commands\SendMessage;
use FondBot\Foundation\Commands\SendRequest;
use FondBot\Foundation\Commands\SendAttachment;

trait SendsMessages
{
    /**
     * Send message to user.
     *
     * @param string|null   $text
     * @param Template|null $template
     * @param int           $delay
     */
    protected function sendMessage(string $text = null, Template $template = null, int $delay = 0): void
    {
        /** @var Dispatcher $dispatcher */
        $dispatcher = Container::getInstance()->make(Dispatcher::class);

        $dispatcher->dispatch(
            (new SendMessage(kernel()->getSession()->getChat(), kernel()->getSession()->getUser(), $text, $template))->delay($delay)
        );
    }

    /**
     * Send attachment to user.
     *
     * @param Attachment $attachment
     * @param int        $delay
     */
    protected function sendAttachment(Attachment $attachment, int $delay = 0): void
    {
        /** @var Dispatcher $dispatcher */
        $dispatcher = Container::getInstance()->make(Dispatcher::class);

        $dispatcher->dispatch(
            (new SendAttachment(kernel()->getSession()->getChat(), kernel()->getSession()->getUser(), $attachment))->delay($delay)
        );
    }

    /**
     * Send request to the messaging service.
     *
     * @param string $endpoint
     * @param array  $parameters
     * @param int    $delay
     */
    protected function sendRequest(string $endpoint, array $parameters = [], int $delay = 0): void
    {
        /** @var Dispatcher $dispatcher */
        $dispatcher = Container::getInstance()->make(Dispatcher::class);

        $dispatcher->dispatch(
            (new SendRequest(kernel()->getSession()->getChat(), kernel()->getSession()->getUser(), $endpoint, $parameters))->delay($delay)
        );
    }
}
