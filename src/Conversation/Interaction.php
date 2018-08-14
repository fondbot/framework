<?php

declare(strict_types=1);

namespace FondBot\Conversation;

use FondBot\Events\MessageReceived;
use FondBot\Contracts\Conversation\Manager;
use FondBot\Contracts\Conversation\Conversable;
use FondBot\Conversation\Concerns\SendsMessages;
use FondBot\Conversation\Concerns\InteractsWithContext;

abstract class Interaction implements Conversable
{
    use InteractsWithContext;
    use SendsMessages;

    /**
     * Run interaction.
     *
     * @param MessageReceived $message
     */
    abstract public function run(MessageReceived $message): void;

    /**
     * Process received message.
     *
     * @param MessageReceived $reply
     */
    abstract public function process(MessageReceived $reply): void;

    /**
     * Jump to interaction.
     *
     * @throws \InvalidArgumentException
     */
    public static function jump(): void
    {
        /** @var Manager $conversation */
        $conversation = resolve(Manager::class);
        $conversation->converse(resolve(static::class));
        $conversation->markAsTransitioned();
    }

    /**
     * Restart current interaction.
     */
    protected function restart(): void
    {
        /** @var Manager $conversation */
        $conversation = resolve(Manager::class);
        $conversation->restartInteraction($this);
    }

    /**
     * Handle interaction.
     *
     * @param MessageReceived $message
     */
    public function handle(MessageReceived $message): void
    {
        $context = context();

        if ($context->getInteraction() instanceof $this) {
            $this->process($message);
        } else {
            $context->setInteraction($this);
            $this->run($message);
        }
    }
}
