<?php

declare(strict_types=1);

namespace FondBot\Foundation;

use FondBot\Drivers\Driver;
use FondBot\Channels\Channel;
use FondBot\Conversation\Context;
use FondBot\Conversation\Session;
use FondBot\Drivers\DriverManager;
use FondBot\Conversation\ContextManager;
use FondBot\Conversation\SessionManager;
use Illuminate\Contracts\Container\Container;

class Kernel
{
    public const VERSION = '2.0';

    private $container;

    /** @var Channel */
    private $channel;

    /** @var Driver */
    private $driver;

    private $session;
    private $context;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Initialize kernel.
     *
     * @param Channel $channel
     */
    public function initialize(Channel $channel): void
    {
        // Set channel
        $this->channel = $channel;

        // Resolve channel driver and initialize it
        $this->driver = $this->driverManager()->driver($channel->getDriver());
        $this->driver->initialize($channel->getParameters());
    }

    /**
     * Perform shutdown tasks.
     */
    public function terminate(): void
    {
        // Save session if exists
        if ($this->session !== null) {
            $this->sessionManager()->save($this->session);
        }

        // Save context if exists
        if ($this->context !== null) {
            $this->contextManager()->save($this->context);
        }
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
     * Get current driver.
     *
     * @return Driver|null
     */
    public function getDriver(): ?Driver
    {
        return $this->driver;
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
     */
    public function closeSession(): void
    {
        if ($this->session !== null) {
            $this->sessionManager()->close($this->session);
            $this->session = null;
        }
    }

    /**
     * Get context.
     *
     * @return Context|null
     */
    public function getContext(): ?Context
    {
        return $this->context;
    }

    /**
     * Set context.
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
     * Get session manager.
     *
     * @return SessionManager
     */
    private function sessionManager(): SessionManager
    {
        return $this->container->make(SessionManager::class);
    }

    /**
     * Get context manager.
     *
     * @return ContextManager
     */
    private function contextManager(): ContextManager
    {
        return $this->container->make(ContextManager::class);
    }

    /**
     * Get driver manager.
     *
     * @return DriverManager
     */
    private function driverManager(): DriverManager
    {
        return $this->container->make(DriverManager::class);
    }
}
