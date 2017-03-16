<?php

declare(strict_types=1);

namespace FondBot\Contracts\Events;

use FondBot\Channels\Receiver;
use FondBot\Conversation\Context;

class MessageSent
{
    private $context;
    private $receiver;
    private $text;

    public function __construct(
        Context $context,
        Receiver $receiver,
        string $text
    ) {
        $this->context = $context;
        $this->receiver = $receiver;
        $this->text = $text;
    }

    public function getContext(): Context
    {
        return $this->context;
    }

    public function getReceiver(): Receiver
    {
        return $this->receiver;
    }

    public function getText(): string
    {
        return $this->text;
    }
}
