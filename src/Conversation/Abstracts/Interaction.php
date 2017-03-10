<?php declare(strict_types=1);

namespace FondBot\Conversation\Abstracts;

use FondBot\Channels\Objects\Message;
use FondBot\Conversation\Context;
use FondBot\Conversation\Traits\Transitions;
use FondBot\Traits\Loggable;

abstract class Interaction
{

    /** @var Context */
    protected $context;

    use Loggable, Transitions;

    /**
     * Do something before running Interaction
     */
    public function before(): void
    {

    }

    /**
     * Do something after running Interaction
     */
    public function after(): void
    {

    }

    /**
     * Message to be sent to Participant
     *
     * @return Message
     */
    abstract public function message(): Message;

    /**
     * Keyboard to be shown to Participant
     *
     * @return Keyboard|null
     */
    abstract public function keyboard(): ?Keyboard;

    /**
     * Process reply
     */
    abstract protected function process(): void;

    public function run(Context $context): void
    {
        $this->debug('run');

        $this->context = $context;

        // Perform actions before running interaction
        $this->before();

        // Process reply if current Interaction in Context
        // Generate reply if not
        if ($context->getInteraction() instanceof $this) {
            $this->debug('process');
            $this->process();
        } else {
            $this->debug('reply');

            // Update interaction in context
            $this->context->setInteraction($this);
            $this->context->save();

            // Compose message
            $channel = $context->getChannel();
            $channel->reply($channel->participant(), $this->message(), $this->keyboard());
        }

        // Perform actions before running interaction
        $this->after();
    }

}