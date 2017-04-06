<?php

declare(strict_types=1);

namespace FondBot\Conversation\Traits;

use FondBot\Bot;
use FondBot\Queue\Queue;
use FondBot\Drivers\User;
use FondBot\Conversation\Keyboard;
use FondBot\Drivers\Commands\SendMessage;
use FondBot\Drivers\Commands\SendAttachment;
use FondBot\Drivers\ReceivedMessage\Attachment;

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
     * Send message to user with delay.
     *
     * @param int           $delay
     * @param string        $text
     * @param Keyboard|null $keyboard
     */
    protected function sendDelayedMessage(int $delay, string $text, Keyboard $keyboard = null): void
    {
        /** @var Queue $queue */
        $queue = $this->bot->get(Queue::class);

        $queue->later($this->bot->getDriver(), new SendMessage($this->user(), $text, $keyboard), $delay);
    }

    /**
     * Send attachment to user.
     *
     * @param Attachment $attachment
     * @param int        $delay
     */
    protected function sendAttachment(Attachment $attachment, int $delay = 0): void
    {
        /** @var Queue $queue */
        $queue = $this->bot->get(Queue::class);

        if ($delay === 0) {
            $queue->push($this->bot->getDriver(), new SendAttachment($this->user(), $attachment));
        } else {
            $queue->later($this->bot->getDriver(), new SendAttachment($this->user(), $attachment), $delay);
        }
    }

    /**
     * Get user.
     *
     * @return User
     */
    abstract protected function user(): User;
}
