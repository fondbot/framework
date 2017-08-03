<?php

declare(strict_types=1);

namespace FondBot\Foundation;

use FondBot\Drivers\Driver;
use FondBot\Drivers\Command;
use FondBot\Channels\Channel;
use FondBot\Conversation\Context;
use FondBot\Conversation\Session;
use FondBot\Conversation\ContextManager;
use FondBot\Conversation\SessionManager;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Contracts\Container\Container;

class Kernel
{
    public const VERSION = '2.0';

    /** @var Kernel */
    private static $instance;

    private $container;
    private $terminable;

    private $driver;
    private $channel;
    private $session;
    private $context;

    private function __construct(Container $container, bool $terminable = true)
    {
        $this->container = $container;
        $this->terminable = $terminable;
    }

    public function __destruct()
    {
        $this->terminate();
    }

    public static function getInstance(): Kernel
    {
        return static::$instance;
    }

    public static function createInstance(Container $container, bool $terminable = true): Kernel
    {
        return static::$instance = new static($container, $terminable);
    }

    /**
     * Perform shutdown tasks.
     */
    public function terminate(): void
    {
        if (!$this->terminable) {
            return;
        }

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
     * Resolve an alias from container.
     *
     * @param string $alias
     *
     * @return mixed
     */
    public function resolve(string $alias)
    {
        return $this->container->make($alias);
    }

    /**
     * Dispatch command to driver.
     *
     * @param Command $command
     */
    public function dispatch(Command $command): void
    {
        $dispatcher = $this->resolve(Dispatcher::class);

        $dispatcher->dispatch($command);
    }

    /**
     * Get session manager.
     *
     * @return SessionManager
     */
    private function sessionManager(): SessionManager
    {
        return $this->resolve(SessionManager::class);
    }

    /**
     * Get context manager.
     *
     * @return ContextManager
     */
    private function contextManager(): ContextManager
    {
        return $this->resolve(ContextManager::class);
    }
}
