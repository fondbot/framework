<?php

declare(strict_types=1);

namespace FondBot\Conversation;

use FondBot\Contracts\Conversable;
use FondBot\Events\MessageReceived;
use FondBot\Conversation\Concerns\Authorization;
use FondBot\Conversation\Concerns\SendsMessages;
use FondBot\Conversation\Concerns\InteractsWithContext;

abstract class Intent implements Conversable
{
    use InteractsWithContext;
    use SendsMessages;
    use Authorization;

    /**
     * Intent activators.
     *
     * @return \FondBot\Contracts\Activator[]
     */
    abstract public function activators(): array;

    /**
     * Run intent.
     *
     * @param MessageReceived $message
     */
    abstract public function run(MessageReceived $message): void;

    /**
     * Handle intent.
     *
     * @param MessageReceived $message
     */
    public function handle(MessageReceived $message): void
    {
        $this->run($message);
    }
}
