<?php

declare(strict_types=1);

namespace FondBot\Drivers;

use FondBot\Channels\Chat;
use FondBot\Channels\User;
use FondBot\Channels\Channel;
use FondBot\Contracts\Template;
use FondBot\Templates\Attachment;
use FondBot\Foundation\Commands\SendMessage;
use FondBot\Foundation\Commands\SendAttachment;

class PendingReply
{
    private $channel;
    private $chat;
    private $user;
    private $text;
    private $template;
    private $attachment;
    private $delay;

    public function __construct(Channel $channel, Chat $chat, User $user)
    {
        $this->channel = $channel;
        $this->chat = $chat;
        $this->user = $user;
    }

    /**
     * Set reply text.
     *
     * @param null|string $text
     *
     * @return static
     */
    public function text(?string $text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Set template for reply.
     *
     * @param Template|null $template
     *
     * @return static
     */
    public function template(?Template $template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * Set attachment for send.
     *
     * @param Attachment $attachment
     *
     * @return static
     */
    public function attachment(Attachment $attachment)
    {
        $this->attachment = $attachment;

        return $this;
    }

    /**
     * Set the desired delay for the job.
     *
     * @param  \DateTime|int|null $delay
     *
     * @return static
     */
    public function delay($delay)
    {
        $this->delay = $delay;

        return $this;
    }

    public function __destruct()
    {
        if ($this->text) {
            SendMessage::dispatch(
                $this->channel,
                $this->chat,
                $this->user,
                $this->text,
                $this->template
            )->delay($this->delay);
        }

        if ($this->attachment) {
            SendAttachment::dispatch(
                $this->channel,
                $this->chat,
                $this->user,
                $this->attachment
            )->delay($this->delay);
        }
    }
}
