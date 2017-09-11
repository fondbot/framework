<?php

declare(strict_types=1);

namespace FondBot\Contracts\Conversation;

use FondBot\Channels\Chat;
use FondBot\Channels\User;
use FondBot\Channels\Channel;
use FondBot\Conversation\Intent;
use FondBot\Conversation\Context;
use FondBot\Events\MessageReceived;

interface Manager
{
    /**
     * Register intent.
     *
     * @param string $class
     */
    public function registerIntent(string $class): void;

    /**
     * Register fallback intent.
     *
     * @param string $class
     */
    public function registerFallbackIntent(string $class): void;

    /**
     * Get all registered intents.
     *
     * @return array
     */
    public function getIntents(): array;

    /**
     * Match intent by received message.
     *
     * @param MessageReceived $messageReceived
     *
     * @return Intent|null
     */
    public function matchIntent(MessageReceived $messageReceived): ?Intent;

    /**
     * Resolve conversation context.
     *
     * @param Channel $channel
     * @param Chat    $chat
     * @param User    $user
     *
     * @return Context
     */
    public function resolveContext(Channel $channel, Chat $chat, User $user): Context;

    /**
     * Save context.
     *
     * @param Context $context
     */
    public function saveContext(Context $context): void;

    /**
     * Flush context.
     *
     * @param Context $context
     */
    public function flushContext(Context $context): void;

    /**
     * Get current context.
     *
     * @return Context|null
     */
    public function getContext(): ?Context;

    /**
     * Define received message.
     *
     * @param MessageReceived $messageReceived
     */
    public function setReceivedMessage(MessageReceived $messageReceived): void;

    /**
     * Mark conversation as transitioned.
     */
    public function markAsTransitioned(): void;

    /**
     * Determine if conversation has been transitioned.
     *
     * @return bool
     */
    public function transitioned(): bool;

    /**
     * Start conversation.
     *
     * @param Conversable     $conversable
     */
    public function converse(Conversable $conversable): void;

    /**
     * Transition to intent or interaction.
     *
     * @param string $conversable
     */
    public function transition(string $conversable): void;

    /**
     * Restart current dialog.
     *
     * @param Conversable $conversable
     */
    public function restart(Conversable $conversable): void;
}
