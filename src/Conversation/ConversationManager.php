<?php

declare(strict_types=1);

namespace FondBot\Conversation;

use FondBot\Channels\Chat;
use FondBot\Channels\User;
use FondBot\Channels\Channel;
use InvalidArgumentException;
use Illuminate\Cache\Repository;
use FondBot\Events\MessageReceived;
use FondBot\Contracts\Conversation\Manager;
use FondBot\Contracts\Conversation\Conversable;
use Illuminate\Contracts\Foundation\Application;

class ConversationManager implements Manager
{
    private $intents = [];
    private $fallbackIntent;

    private $application;
    private $cache;

    private $transitioned = false;

    private $messageReceived;

    public function __construct(Application $application, Repository $cache)
    {
        $this->application = $application;
        $this->cache = $cache;
    }

    /**
     * Register intent.
     *
     * @param string $class
     */
    public function registerIntent(string $class): void
    {
        $this->intents[] = $class;
    }

    /**
     * Register fallback intent.
     *
     * @param string $class
     */
    public function registerFallbackIntent(string $class): void
    {
        $this->fallbackIntent = $class;
    }

    /**
     * Get all registered intents.
     *
     * @return array
     */
    public function getIntents(): array
    {
        return $this->intents;
    }

    /**
     * Match intent by received message.
     *
     * @param MessageReceived $messageReceived
     *
     * @return Intent|null
     */
    public function matchIntent(MessageReceived $messageReceived): ?Intent
    {
        foreach ($this->intents as $intent) {
            /** @var Intent $intent */
            $intent = resolve($intent);

            foreach ($intent->activators() as $activator) {
                if ($activator->matches($messageReceived) && $intent->passesAuthorization($messageReceived)) {
                    return $intent;
                }
            }
        }

        // Otherwise, return fallback intent
        return resolve($this->fallbackIntent);
    }

    /**
     * Resolve conversation context.
     *
     * @param Channel $channel
     * @param Chat    $chat
     * @param User    $user
     *
     * @return Context|null
     */
    public function resolveContext(Channel $channel, Chat $chat, User $user): Context
    {
        $value = $this->cache->get($this->getCacheKeyForContext($channel, $chat, $user), [
            'chat' => $chat,
            'user' => $user,
            'intent' => null,
            'interaction' => null,
        ]);

        $context = new Context($channel, $chat, $user);

        if ($value['intent'] !== null) {
            $context->setIntent($value['intent']);
        }

        if ($value['interaction'] !== null) {
            $context->setInteraction($value['interaction']);
        }

        // Bind resolved instance to the container
        $this->application->instance('fondbot.conversation.context', $context);

        return $context;
    }

    /**
     * Save context.
     *
     * @param Context $context
     */
    public function saveContext(Context $context): void
    {
        $this->cache->forever(
            $this->getCacheKeyForContext($context->getChannel(), $context->getChat(), $context->getUser()),
            $context->toArray()
        );
    }

    /**
     * Flush context.
     *
     * @param Context $context
     */
    public function flushContext(Context $context): void
    {
        $this->cache->forget(
            $this->getCacheKeyForContext($context->getChannel(), $context->getChat(), $context->getUser())
        );
    }

    /**
     * Get current context.
     *
     * @return Context|null
     */
    public function getContext(): ?Context
    {
        if (!$this->application->has('fondbot.conversation.context')) {
            return null;
        }

        return $this->application->get('fondbot.conversation.context');
    }

    /**
     * Define received message.
     *
     * @param MessageReceived $messageReceived
     */
    public function setReceivedMessage(MessageReceived $messageReceived): void
    {
        $this->messageReceived = $messageReceived;
    }

    /**
     * Mark conversation as transitioned.
     */
    public function markAsTransitioned(): void
    {
        $this->transitioned = true;
    }

    /**
     * Determine if conversation has been transitioned.
     *
     * @return bool
     */
    public function transitioned(): bool
    {
        return $this->transitioned;
    }

    /**
     * Start conversation.
     *
     * @param Conversable $conversable
     */
    public function converse(Conversable $conversable): void
    {
        if ($conversable instanceof Intent) {
            context()->setIntent($conversable)->setInteraction(null);
        }

        $conversable->handle($this->messageReceived);
    }

    /**
     * Jump to interaction.
     *
     * @param string $conversable
     */
    public function transition(string $conversable): void
    {
        /** @var Interaction $instance */
        $instance = resolve($conversable);

        if (!$instance instanceof Conversable) {
            throw new InvalidArgumentException('Invalid conversable `'.$conversable.'`');
        }

        $this->converse($instance, $this->messageReceived);
        $this->markAsTransitioned();
    }

    /**
     * Restart current dialog.
     *
     * @param Conversable $conversable
     */
    public function restart(Conversable $conversable): void
    {
        if ($conversable instanceof Intent) {
            $this->markAsTransitioned();
        }

        $this->converse($conversable);

        if ($conversable instanceof Interaction) {
            $this->markAsTransitioned();
        }
    }

    public function __destruct()
    {
        $context = $this->getContext();

        if ($context === null) {
            return;
        }

        // Close session if conversation has not been transitioned
        if (!$this->transitioned()) {
            $this->flushContext($context);
        }

        // Save context if exists
        if ($context = context()) {
            $this->saveContext($context);
        }
    }

    private function getCacheKeyForContext(Channel $channel, Chat $chat, User $user): string
    {
        return implode('.', ['context', $channel->getName(), $chat->getId(), $user->getId()]);
    }
}
