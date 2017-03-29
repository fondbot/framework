<?php

declare(strict_types=1);

namespace FondBot;

use FondBot\Traits\Loggable;
use FondBot\Channels\Channel;
use FondBot\Conversation\Context;
use FondBot\Contracts\Channels\User;
use FondBot\Contracts\Channels\Driver;
use FondBot\Conversation\StoryManager;
use FondBot\Conversation\ContextManager;
use FondBot\Contracts\Conversation\Story;
use FondBot\Contracts\Container\Container;
use FondBot\Contracts\Conversation\Keyboard;
use FondBot\Contracts\Conversation\Conversable;
use FondBot\Contracts\Conversation\Interaction;
use FondBot\Channels\Exceptions\InvalidChannelRequest;
use FondBot\Contracts\Channels\Extensions\WebhookVerification;

class Bot
{
    use Loggable;

    /** @var Bot */
    private static $instance;

    private $container;
    private $channel;
    private $driver;
    private $request;
    private $headers;

    /** @var Context|null */
    private $context;

    protected function __construct(
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
     * Create new bot instance.
     *
     * @param Container $container
     * @param Channel   $channel
     * @param Driver    $driver
     * @param array     $request
     * @param array     $headers
     */
    public static function createInstance(
        Container $container,
        Channel $channel,
        Driver $driver,
        array $request,
        array $headers
    ): void {
        static::setInstance(
            new static($container, $channel, $driver, $request, $headers)
        );
    }

    /**
     * Get current instance.
     *
     * @return Bot
     */
    public static function getInstance(): Bot
    {
        return static::$instance;
    }

    /**
     * Set instance of the bot.
     *
     * @param Bot $instance
     */
    public static function setInstance(Bot $instance): void
    {
        static::$instance = $instance;
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
     * Set context instance.
     *
     * @param Context $context
     */
    public function setContext(Context $context): void
    {
        $this->context = $context;
    }

    /**
     * Clear context.
     */
    public function clearContext(): void
    {
        $this->contextManager()->clear($this->context);
    }

    /**
     * Resolve from container.
     *
     * @param string $class
     *
     * @return mixed
     */
    public function get(string $class)
    {
        return $this->container->make($class);
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
     * @param Conversable $conversable
     */
    public function converse(Conversable $conversable): void
    {
        if ($conversable instanceof Story) {
            $this->context->setStory($conversable);
            $this->context->setInteraction(null);
            $this->context->setValues([]);

            $conversable->handle($this);
        } elseif ($conversable instanceof Interaction) {
            $conversable->handle($this);

            $this->context->setInteraction($conversable);
        }
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

    /**
     * Get context manager.
     *
     * @return ContextManager
     */
    private function contextManager(): ContextManager
    {
        return $this->get(ContextManager::class);
    }

    /**
     * Get story manager.
     *
     * @return StoryManager
     */
    private function storyManager(): StoryManager
    {
        return $this->get(StoryManager::class);
    }
}
