<?php declare(strict_types=1);

namespace FondBot\Conversation\Traits;

use FondBot\Conversation\Abstracts\Interaction;
use FondBot\Conversation\Context;
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
        if ($this->context === null) {
            throw new InvalidArgumentException('Context cannot be `null`');
        }

        /** @var Interaction $instance */
        $instance = app($interaction);

        if (!$instance instanceof Interaction) {
            throw new InvalidArgumentException($interaction . ' is not a valid "Interaction".');
        }

        // Run interaction
        $instance->run($this->context);
    }

}