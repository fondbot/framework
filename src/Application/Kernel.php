<?php

declare(strict_types=1);

namespace FondBot\Application;

use FondBot\Http\Request;
use FondBot\Drivers\Driver;
use FondBot\Channels\Channel;
use League\Container\Container;
use FondBot\Conversation\Intent;
use FondBot\Conversation\Session;
use FondBot\Drivers\DriverManager;
use FondBot\Conversation\Conversable;
use FondBot\Conversation\Interaction;
use FondBot\Conversation\IntentManager;
use FondBot\Conversation\SessionManager;
use FondBot\Drivers\Exceptions\InvalidRequest;
use FondBot\Drivers\Extensions\WebhookVerification;

class Kernel
{
    public const VERSION = '1.0.0';

    /** @var Kernel */
    protected static $instance;

    private $container;

    /** @var Session|null */
    private $session;

    private function __construct(Container $container)
    {
        $this->container = $container;
    }

    public static function getInstance(): Kernel
    {
        return static::$instance;
    }

    public static function createInstance(Container $container): Kernel
    {
        return static::$instance = new static($container);
    }

    /**
     * Get current channel.
     *
     * @return Channel
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     */
    public function getChannel(): Channel
    {
        return $this->container->get('channel');
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
     * Get session.
     *
     * @return Session|null
     */
    public function getSession(): ?Session
    {
        return $this->session;
    }

    /**
     * Set session.
     *
     * @param Session $session
     */
    public function setSession(Session $session): void
    {
        $this->session = $session;
    }

    /**
     * Close session.
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     */
    public function closeSession(): void
    {
        if ($this->session !== null) {
            $this->sessionManager()->close($this->session);
            $this->session = null;
        }
    }

    /**
     * Resolve an alias from container.
     *
     * @param string $alias
     * @param array  $args
     *
     * @return mixed
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     */
    public function resolve(string $alias, array $args = [])
    {
        return $this->container->get($alias, $args);
    }

    /**
     * Process webhook request.
     *
     * @param Channel $channel
     * @param Request $request
     *
     * @return mixed
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \FondBot\Drivers\Exceptions\DriverNotFound
     * @throws \FondBot\Drivers\Exceptions\InvalidConfiguration
     */
    public function process(Channel $channel, Request $request)
    {
        try {
            $driver = $this->driverManager()->get($channel, $request);

            $this->container->add('channel', $channel);
            $this->container->add('driver', $driver);

            // Driver has webhook verification
            if ($driver instanceof WebhookVerification && $driver->isVerificationRequest()) {
                return $driver->verifyWebhook();
            }

            // Verify request
            $driver->verifyRequest();

            // Resolve session
            $this->session = $this->sessionManager()->load($channel->getName(), $driver);

            if ($this->session->getInteraction() !== null) {
                $this->converse($this->session->getInteraction());
            } else {
                $intent = $this->intentManager()->find($driver->getMessage());

                if ($intent !== null) {
                    $this->converse($intent);
                }
            }

            if ($this->session !== null) {
                $this->sessionManager()->save($this->session);
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
            $this->session->setIntent($conversable);
            $this->session->setInteraction(null);
            $this->session->setValues([]);

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
     * Get session manager.
     *
     * @return SessionManager
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     */
    private function sessionManager(): SessionManager
    {
        return $this->resolve(SessionManager::class);
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
