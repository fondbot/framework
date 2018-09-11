<?php

declare(strict_types=1);

namespace FondBot;

use FondBot\Channels\Channel;

class FondBot
{
    public const VERSION = '4.0.0';

    /** @var Channel|null */
    private $channel;

    /**
     * Initialize kernel.
     *
     * @param Channel $channel
     */
    public function initialize(Channel $channel): void
    {
        $this->channel = $channel;
    }

    /**
     * Get current channel.
     *
     * @return Channel|null
     */
    public function getChannel(): ?Channel
    {
        return $this->channel;
    }
}
