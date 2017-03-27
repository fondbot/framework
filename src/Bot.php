<?php

declare(strict_types=1);

namespace FondBot;

use FondBot\Channels\Channel;
use FondBot\Contracts\Channels\Driver;
use FondBot\Contracts\Channels\User;
use FondBot\Contracts\Conversation\Keyboard;
use FondBot\Conversation\Context;
use FondBot\Conversation\ContextManager;
use FondBot\Conversation\Interaction;
use FondBot\Conversation\Story;
use FondBot\Conversation\StoryManager;
use FondBot\Traits\Loggable;
use FondBot\Channels\Exceptions\InvalidChannelRequest;
use FondBot\Contracts\Channels\Extensions\WebhookVerification;
use Illuminate\Contracts\Container\Container;

class Bot
{
    use Loggable;

    private $container;
    private $channel;
    private $driver;
    private $request;
    private $headers;

    /** @var Context|null */
    private $context;

    public function __construct(
        Container $container,
        Channel $channel,
        Driver $driver,
        array $request,
        array $headers
    ) {
        $this->container = $container;
        $this->channel = $channel;
        $this->driver = $driver;
        $this->request = $request;
        $this->headers = $headers;
    }

    /**
     * Get context instance.
     *
     * @return Context
     */
    public function getContext(): Context
    {
        return $this->context;
    }

    /**
     * Clear context.
     */
    public function clearContext(): void
    {
        $this->contextManager()->clear($this->context);
    }

    /**
     * Process webhook request.
     *
     * @return mixed
     */
    public function process()
    {
        try {
            $this->debug('process', [
                'channel' => $this->channel->getName(),
                'request' => $this->request,
                'headers' => $this->headers,
            ]);

            // Driver has webhook verification
            if ($this->driver instanceof WebhookVerification && $this->driver->isVerificationRequest()) {
                $this->debug('process.verifyWebhook');

                return $this->driver->verifyWebhook();
            }

            // Verify request
            $this->driver->verifyRequest();

            // Resolve context
            $this->context = $this->contextManager()->resolve($this->channel->getName(), $this->driver);

            // Start or resume conversation
            $story = $this->storyManager()->find($this->context, $this->driver->getMessage());

            if ($story !== null) {
                $this->converse($story);
            }

            $this->contextManager()->save($this->context);

            return 'OK';
        } catch (InvalidChannelRequest $exception) {
            $this->error($exception->getMessage());

            return $exception->getMessage();
        }
    }

    /**
     * Start conversation.
     *
     * @param Story            $story
     * @param Interaction|null $interaction
     */
    public function converse(Story $story, Interaction $interaction = null): void
    {
        // Remember story in context
        $this->context->setStory($story);
        $this->context->setInteraction($interaction);
        $this->context->setValues([]);

        // Execute story
        $story->handle($this);
    }

    /**
     * Send message.
     *
     * @param User          $recipient
     * @param string        $text
     * @param Keyboard|null $keyboard
     */
    public function sendMessage(User $recipient, string $text, Keyboard $keyboard = null): void
    {
        $this->driver->sendMessage(
            $recipient,
            $text,
            $keyboard
        );
    }

    private function contextManager(): ContextManager
    {
        return $this->container->make(ContextManager::class);
    }

    private function storyManager(): StoryManager
    {
        return $this->container->make(StoryManager::class);
    }
}
