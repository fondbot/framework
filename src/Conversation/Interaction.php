<?php

declare(strict_types=1);

namespace FondBot\Conversation;

use FondBot\Traits\Loggable;
use FondBot\Contracts\Channels\Receiver;
use Illuminate\Contracts\Bus\Dispatcher;
use FondBot\Conversation\Traits\Transitions;
use FondBot\Contracts\Channels\SenderMessage;
use FondBot\Conversation\Commands\SendMessage;
use FondBot\Contracts\Conversation\Interaction as InteractionContract;

abstract class Interaction implements InteractionContract
{
    use Transitions, Loggable;

    /**
     * Get message receiver.
     *
     * @return Receiver
     */
    public function getReceiver(): Receiver
    {
        $sender = $this->getContext()->getSender();

        return new Receiver($sender->getIdentifier(), $sender->getName(), $sender->getUsername());
    }

    /**
     * Get sender's message.
     *
     * @return SenderMessage
     */
    public function getSenderMessage(): SenderMessage
    {
        return $this->getContext()->getMessage();
    }

    /**
     * Do something before running Interaction.
     */
    protected function before(): void
    {
    }

    /**
     * Do something after running Interaction.
     */
    protected function after(): void
    {
    }

    /**
     * Run interaction.
     */
    public function run(): void
    {
        $this->debug('run');

        // Perform actions before running interaction
        $this->before();

        // Process reply if current interaction in context
        // Reply to participant if not
        if ($this->context->getInteraction() instanceof $this) {
            $this->debug('run.process');
            $this->process();

            // If no transition run we need to clear context.
            if (!$this->transitioned) {
                $this->clearContext();
            }

            $this->after();

            return;
        }

        // Set current interaction in context
        $this->context->setInteraction($this);

        // Send message to participant
        $this->getDispatcher()->dispatch(
            (new SendMessage(
                $this->getContext()->getChannel(),
                $this->getReceiver(),
                $this->text(),
                $this->keyboard()
            ))->onQueue('fondbot')
        );

        $this->updateContext();

        // Perform actions after running interaction
        $this->after();
    }

    /**
     * Process reply.
     */
    abstract protected function process(): void;

    private function getDispatcher(): Dispatcher
    {
        return resolve(Dispatcher::class);
    }
}
