<?php

declare(strict_types=1);

namespace FondBot\Contracts\Channels;

interface Sender
{
    /**
     * Identifier.
     *
     * @return string
     */
    public function getId(): string;

    /**
     * Full name.
     *
     * @return string|null
     */
    public function getName(): ?string;

    /**
     * Username.
     *
     * @return string|null
     */
    public function getUsername(): ?string;
}
