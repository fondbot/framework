<?php

declare(strict_types=1);

namespace FondBot\Conversation;

use FondBot\Traits\Loggable;
use FondBot\Channels\Objects\Message;
use FondBot\Conversation\Traits\Transitions;

abstract class Interaction
{
    use Loggable, Transitions;

    /** @var Context */
    protected $context;

    /**
     * Do something before running Interaction.
     */
    public function before(): void
    {
    }

    /**
     * Do something after running Interaction.
     */
    public function after(): void
    {
    }

    /**
     * Message to be sent to Participant.
     *
     * @return Message
     */
    abstract public function message(): Message;

    /**
     * Keyboard to be shown to Participant.
     *
     * @return Keyboard|null
     */
    abstract public function keyboard(): ?Keyboard;

    /**
     * Process reply.
     */
    abstract protected function process(): void;

    public function run(Context $context): void
    {
        $this->context = $context;

        // Perform actions before running interaction
        $this->before();

        // Process reply if current interaction in context
        // Reply to participant if not
        if ($context->getInteraction() instanceof $this) {
            $this->process();
        } else {
            // Update interaction in context
            $this->context->setInteraction($this);

            /** @var ContextManager $contextManager */
            $contextManager = resolve(ContextManager::class);
            $contextManager->save($this->context);

            // Send reply to participant
            $driver = $context->getDriver();
            $driver->reply($driver->getParticipant(), $this->message(), $this->keyboard());
        }

        // Perform actions before running interaction
        $this->after();
    }
}
