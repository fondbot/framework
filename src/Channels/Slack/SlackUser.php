<?php
declare(strict_types=1);
namespace FondBot\Channels\Slack;

use FondBot\Contracts\Channels\User;

class SlackUser implements User
{
    private $payload;

    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }

    /**
     * Identifier.
     *
     * @return string
     */
    public function getId(): string
    {
        return (string) $this->payload['user']['id'];
    }

    /**
     * Full name.
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->payload['user']['profile']['first_name'] . ' ' . $this->payload['user']['profile']['last_name'];
    }

    /**
     * Username.
     *
     * @return string|null
     */
    public function getUsername(): ?string
    {
        return $this->payload['user']['name'];
    }
}





