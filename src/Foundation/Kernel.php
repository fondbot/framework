<?php

declare(strict_types=1);

namespace FondBot\Foundation;

use FondBot\Channels\Channel;

class Kernel
{
    public const VERSION = '3.0.22';

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
