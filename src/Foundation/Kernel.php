<?php

declare(strict_types=1);

namespace FondBot\Foundation;

use FondBot\Channels\Channel;
use FondBot\Conversation\Context;
use Illuminate\Contracts\Container\Container;
use FondBot\Foundation\Commands\TerminateKernel;

class Kernel
{
    public const VERSION = '2.0';

    private $container;

    /** @var Channel|null */
    private $channel;

    /** @var Context|null */
    private $context;

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

        TerminateKernel::dispatch();
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

    /**
     * Get context.
     *
     * @return Context|null
     */
    public function getContext(): ?Context
    {
        return $this->context;
    }

    /**
     * Set context.
     *
     * @param Context $context
     */
    public function setContext(Context $context): void
    {
        $this->context = $context;
    }
}
