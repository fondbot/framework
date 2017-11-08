<?php

declare(strict_types=1);

namespace FondBot\Conversation\Activators;

use FondBot\Templates\Attachment;
use FondBot\Events\MessageReceived;
use FondBot\Contracts\Conversation\Activator;

class WithAttachment implements Activator
{
    protected $type;

    public function __construct(string $type = null)
    {
        $this->type = $type;
    }

    public function file(): self
    {
        $this->type = Attachment::TYPE_FILE;

        return $this;
    }

    public function image(): self
    {
        $this->type = Attachment::TYPE_IMAGE;

        return $this;
    }

    public function audio(): self
    {
        $this->type = Attachment::TYPE_AUDIO;

        return $this;
    }

    public function video(): self
    {
        $this->type = Attachment::TYPE_VIDEO;

        return $this;
    }

    /**
     * Result of matching activator.
     *
     * @param MessageReceived $message
     *
     * @return bool
     */
    public function matches(MessageReceived $message): bool
    {
        if ($this->type === null) {
            return $message->getAttachment() !== null;
        }

        return hash_equals($message->getAttachment()->getType(), $this->type);
    }
}
