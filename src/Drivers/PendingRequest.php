<?php

declare(strict_types=1);

namespace FondBot\Drivers;

use FondBot\Channels\Chat;
use FondBot\Channels\User;
use FondBot\Channels\Channel;
use FondBot\Foundation\Commands\SendRequest;

class PendingRequest
{
    private $channel;
    private $chat;
    private $user;
    private $endpoint;
    private $parameters;
    private $delay;

    public function __construct(Channel $channel, Chat $chat, User $user)
    {
        $this->channel = $channel;
        $this->chat = $chat;
        $this->user = $user;
    }

    /**
     * Set request endpoint.
     *
     * @param string $endpoint
     *
     * @return static
     */
    public function endpoint(string $endpoint)
    {
        $this->endpoint = $endpoint;

        return $this;
    }

    /**
     * Set request parameters.
     *
     * @param $parameters
     *
     * @return static
     */
    public function parameters($parameters)
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * Set the desired delay for the job.
     *
     * @param  \DateTime|int|null $delay
     *
     * @return static
     */
    public function delay($delay)
    {
        $this->delay = $delay;

        return $this;
    }

    public function __destruct()
    {
        if ($this->endpoint) {
            SendRequest::dispatch(
                $this->channel,
                $this->chat,
                $this->user,
                $this->endpoint,
                $this->parameters
            )->delay($this->delay);
        }
    }
}
