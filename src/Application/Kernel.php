<?php

declare(strict_types=1);

namespace FondBot\Application;

use FondBot\Drivers\Driver;
use FondBot\Channels\Channel;
use League\Container\Container;
use FondBot\Conversation\Session;
use FondBot\Conversation\SessionManager;

class Kernel
{
    public const VERSION = '1.0.4';

    /** @var Kernel */
    private static $instance;

    private $container;

    private $driver;
    private $channel;
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
     * @return Channel|null
     */
    public function getChannel(): ?Channel
    {
        return $this->channel;
    }

    /**
     * Set channel.
     *
     * @param Channel $channel
     */
    public function setChannel(Channel $channel): void
    {
        $this->channel = $channel;
    }

    /**
     * Get current driver.
     *
     * @return Driver|null
     */
    public function getDriver(): ?Driver
    {
        return $this->driver;
    }

    /**
     * Set driver.
     *
     * @param Driver $driver
     */
    public function setDriver(Driver $driver): void
    {
        $this->driver = $driver;
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
     * Load session.
     *
     * @param Channel $channel
     * @param Driver  $driver
     */
    public function loadSession(Channel $channel, Driver $driver): void
    {
        $this->session = $this->sessionManager()->load($channel, $driver);
    }

    /**
     * Save session.
     */
    public function saveSession(): void
    {
        if ($this->session !== null) {
            $this->sessionManager()->save($this->session);
        }
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
}
