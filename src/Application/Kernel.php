<?php

declare(strict_types=1);

namespace FondBot\Application;

use FondBot\Drivers\Driver;
use FondBot\Channels\Channel;
use League\Container\Container;
use FondBot\Conversation\Intent;
use FondBot\Conversation\Context;
use FondBot\Drivers\DriverManager;
use FondBot\Conversation\Conversable;
use FondBot\Conversation\Interaction;
use FondBot\Conversation\IntentManager;
use FondBot\Conversation\ContextManager;
use Psr\Http\Message\ServerRequestInterface;
use FondBot\Drivers\Exceptions\InvalidRequest;
use FondBot\Drivers\Extensions\WebhookVerification;

class Kernel
{
    public const VERSION = '1.0.0';

    /** @var Kernel */
    protected static $instance;

    private $container;

    /** @var Context|null */
    private $context;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Get current driver instance.
     *
     * @return Driver
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     */
    public function getDriver(): Driver
    {
        return $this->container->get('driver');
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
     *
     * @throws \Psr\Container\ContainerExceptionInterface
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
     * @throws \Psr\Container\ContainerExceptionInterface
     */
    public function resolve(string $class)
    {
        return $this->container->get($class);
    }

    /**
     * Process webhook request.
     *
     * @param Channel                $channel
     * @param ServerRequestInterface $request
     *
     * @return mixed
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \FondBot\Drivers\Exceptions\DriverNotFound
     */
    public function process(Channel $channel, ServerRequestInterface $request)
    {
        try {
            $driver = $this->driverManager()->get($channel, $request);

            $this->container->add('driver', $driver);

            // Driver has webhook verification
            if ($driver instanceof WebhookVerification && $driver->isVerificationRequest()) {
                return $driver->verifyWebhook();
            }

            // Verify request
            $driver->verifyRequest();

            // Resolve context
            $this->context = $this->contextManager()->resolve($channel->getName(), $driver);

            if ($this->context->getInteraction() !== null) {
                $this->converse($this->context->getInteraction());
            } else {
                $intent = $this->intentManager()->find($driver->getMessage());

                if ($intent !== null) {
                    $this->converse($intent);
                }
            }

            if ($this->context !== null) {
                $this->contextManager()->save($this->context);
            }

            return 'OK';
        } catch (InvalidRequest $exception) {
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
        if ($conversable instanceof Intent) {
            $this->context->setIntent($conversable);
            $this->context->setInteraction(null);
            $this->context->setValues([]);

            $conversable->handle($this);
        } elseif ($conversable instanceof Interaction) {
            $conversable->handle($this);
        }
    }

    /**
     * Get driver manager.
     *
     * @return DriverManager
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     */
    private function driverManager(): DriverManager
    {
        return $this->resolve(DriverManager::class);
    }

    /**
     * Get context manager.
     *
     * @return ContextManager
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     */
    private function contextManager(): ContextManager
    {
        return $this->resolve(ContextManager::class);
    }

    /**
     * Get intent manager.
     *
     * @return IntentManager
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     */
    private function intentManager(): IntentManager
    {
        return $this->resolve(IntentManager::class);
    }
}
