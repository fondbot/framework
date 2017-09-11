<?php

declare(strict_types=1);

namespace FondBot\Foundation;

use FondBot\Channels\Channel;
use Illuminate\Contracts\Container\Container;

class Kernel
{
    public const VERSION = '2.0';

    private $container;

    /** @var Channel|null */
    private $channel;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

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
