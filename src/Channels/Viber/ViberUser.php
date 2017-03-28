<?php

declare(strict_types=1);

namespace FondBot\Channels\Viber;

use FondBot\Contracts\Channels\User;

class ViberUser implements User
{

    /**
     * Identifier.
     *
     * @return string
     */
    public function getId(): string
    {
        // TODO: Implement getId() method.
    }

    /**
     * Full name.
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        // TODO: Implement getName() method.
    }

    /**
     * Username.
     *
     * @return string|null
     */
    public function getUsername(): ?string
    {
        // TODO: Implement getUsername() method.
    }
}