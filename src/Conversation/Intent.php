<?php

declare(strict_types=1);

namespace FondBot\Conversation;

use FondBot\Application\Kernel;
use FondBot\Conversation\Traits\Transitions;
use FondBot\Conversation\Traits\Authorization;
use FondBot\Conversation\Traits\HasActivators;
use FondBot\Conversation\Traits\SendsMessages;
use FondBot\Conversation\Traits\InteractsWithSession;

abstract class Intent implements Conversable
{
    use InteractsWithSession,
        SendsMessages,
        Authorization,
        HasActivators,
        Transitions;

    /**
     * Run intent.
     */
    abstract public function run(): void;

    /**
     * Handle intent.
     *
     * @param Kernel $kernel
     */
    public function handle(Kernel $kernel): void
    {
        $this->kernel = $kernel;
        $this->run();
    }
}
