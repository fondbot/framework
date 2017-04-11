<?php

declare(strict_types=1);

namespace FondBot\Conversation;

use FondBot\Kernel;

interface Conversable
{
    /**
     * Handle.
     *
     * @param Kernel $kernel
     */
    public function handle(Kernel $kernel): void;
}
