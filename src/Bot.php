<?php

declare(strict_types=1);

namespace FondBot;

use FondBot\Traits\Loggable;
use FondBot\Channels\Channel;
use FondBot\Conversation\Context;
use FondBot\Contracts\Channels\User;
use FondBot\Contracts\Channels\Driver;
use FondBot\Conversation\IntentManager;
use FondBot\Conversation\ContextManager;
use FondBot\Contracts\Container\Container;
use FondBot\Contracts\Conversation\Intent;
use FondBot\Contracts\Conversation\Keyboard;
use FondBot\Contracts\Channels\OutgoingMessage;
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
     * @return Context|null
     */
    public function getContext(): ?Context
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
        if ($this->context !== null) {
            $this->contextManager()->clear($this->context);
            $this->context = null;
        }
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

            if ($this->context->getIntent() !== null && $this->context->getInteraction() !== null) {
                $this->converse($this->context->getInteraction());
            } else {
                // Start or resume conversation
                $intent = $this->intentManager()->find($this->context, $this->driver->getMessage());

                if ($intent !== null) {
                    $this->converse($intent);
                }
            }

            if ($this->context !== null) {
                $this->contextManager()->save($this->context);
            }

            return 'OK';
        } catch (InvalidChannelRequest $exception) {
            $this->error($exception->getMessage());

            return $exception->getMessage();
        }
    }

    /**
     * Start conversation.
     *
     * @param Conversable|Intent|Interaction $conversable
     */
    public function converse(Conversable $conversable): void
    {
        if ($conversable instanceof Intent) {
            $this->context->setIntent($conversable);
            $this->context->setInteraction(null);
            $this->context->setValues([]);

            $conversable->handle($this);
        } elseif ($conversable instanceof Interaction) {
            $conversable->handle($this);

            // If context is not cleared remember interaction
            if ($this->context !== null) {
                $this->context->setInteraction($conversable);
            }
        }
    }

    /**
     * Send message.
     *
     * @param User          $recipient
     * @param string        $text
     * @param Keyboard|null $keyboard
     *
     * @return OutgoingMessage
     */
    public function sendMessage(User $recipient, string $text, Keyboard $keyboard = null): OutgoingMessage
    {
        return $this->driver->sendMessage(
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
     * Get intent manager.
     *
     * @return IntentManager
     */
    private function intentManager(): IntentManager
    {
        return $this->get(IntentManager::class);
    }
}
