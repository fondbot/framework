<?php

declare(strict_types=1);

namespace FondBot\Contracts\Events;

use FondBot\Contracts\Channels\ReceiverMessage;
use FondBot\Conversation\Context;

class MessageSent
{
    private $context;
    private $message;

    public function __construct(
        Context $context,
        ReceiverMessage $message
    ) {
        $this->context = $context;
        $this->message = $message;
    }

    public function getContext(): Context
    {
        return $this->context;
    }

    public function getMessage(): ReceiverMessage
    {
        return $this->message;
    }
}
