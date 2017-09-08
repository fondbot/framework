<?php

declare(strict_types=1);

namespace FondBot\Conversation;

use FondBot\Channels\Chat;
use FondBot\Channels\User;
use FondBot\Channels\Channel;
use Illuminate\Cache\Repository;
use FondBot\Events\MessageReceived;
use FondBot\Contracts\Conversation\Manager;
use Illuminate\Contracts\Foundation\Application;

class ConversationManager implements Manager
{
    private $intents = [];
    private $fallbackIntent;

    private $application;
    private $cache;

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

    private function getCacheKeyForContext(Channel $channel, Chat $chat, User $user): string
    {
        return implode('.', ['context', $channel->getName(), $chat->getId(), $user->getId()]);
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
}
