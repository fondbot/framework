<?php

declare(strict_types=1);

namespace FondBot\Conversation\Traits;

use FondBot\Conversation\Context;
use FondBot\Conversation\ContextManager;

trait InteractsWithContext
{

    /** @var Context */
    private $context;

    /**
     * Get current context instance.
     *
     * @return Context
     */
    public function getContext(): Context
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
     * Update context instance.
     */
    protected function updateContext(): void
    {
        $this->getContextManager()->save($this->context);
    }

    /**
     * Clear context instance.
     */
    protected function clearContext(): void
    {
        $this->getContextManager()->clear($this->context);
    }

    /**
     * Get context manager instance.
     *
     * @return ContextManager
     */
    private function getContextManager(): ContextManager
    {
        return resolve(ContextManager::class);
    }

}
