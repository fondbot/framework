<?php declare(strict_types=1);

namespace FondBot\Conversation\Traits;

use FondBot\Conversation\Context;
use FondBot\Conversation\Interaction;
use InvalidArgumentException;

trait Transitions
{

    /** @var Context */
    protected $context;

    /**
     * Jump to another interaction
     *
     * @param string $interaction
     *
     * @throws \InvalidArgumentException
     */
    protected function jump(string $interaction): void
    {
        /** @var Interaction $instance */
        $instance = resolve($interaction);

        if (!$instance instanceof Interaction) {
            throw new InvalidArgumentException($interaction . ' is not a valid "Interaction".');
        }

        // Run interaction
        $instance->run($this->context);
    }

}