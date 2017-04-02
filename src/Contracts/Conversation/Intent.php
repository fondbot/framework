<?php

declare(strict_types=1);

namespace FondBot\Contracts\Conversation;

interface Intent
{
    /**
     * Determine if intent passes the authorization check.
     *
     * @return bool
     */
    public function passesAuthorization(): bool;

    /**
     * Intent activators.
     *
     * @return Activator[]
     */
    public function activators(): array;

    /**
     * Run intent.
     */
    public function run(): void;
}
