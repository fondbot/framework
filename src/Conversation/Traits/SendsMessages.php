<?php

declare(strict_types=1);

namespace FondBot\Conversation\Traits;

use FondBot\Bot;
use FondBot\Drivers\Commands\SendAttachment;
use FondBot\Drivers\Commands\SendMessage;
use FondBot\Drivers\ReceivedMessage\Attachment;
use FondBot\Drivers\User;
use FondBot\Conversation\Keyboard;
use FondBot\Queue\Queue;

trait SendsMessages
{
    /** @var Bot */
    protected $bot;

    /**
     * Send message to user.
     *
     * @param string        $text
     * @param Keyboard|null $keyboard
     */
    protected function sendMessage(string $text, Keyboard $keyboard = null): void
    {
        /** @var Queue $queue */
        $queue = $this->bot->get(Queue::class);

        $queue->push($this->bot->getDriver(), new SendMessage($this->user(), $text, $keyboard));
    }

    /**
     * Send attachment to user.
     *
     * @param Attachment $attachment
     */
    protected function sendAttachment(Attachment $attachment): void
    {
        /** @var Queue $queue */
        $queue = $this->bot->get(Queue::class);

        $queue->push($this->bot->getDriver(), new SendAttachment($this->user(), $attachment));
    }

    /**
     * Get user.
     *
     * @return User
     */
    abstract protected function user(): User;
}
