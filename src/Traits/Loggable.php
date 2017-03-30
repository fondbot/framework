<?php

declare(strict_types=1);

namespace FondBot\Traits;

use FondBot\Bot;
use Psr\Log\LoggerInterface;
use FondBot\Contracts\Core\Arrayable;

trait Loggable
{
    protected function debug(string $message, array $context = []): void
    {
        $class = get_class($this);

        $this->logger()->debug($class.'.'.$message, $this->prepareContext($context));
    }

    protected function error(string $message, array $context = []): void
    {
        $class = get_class($this);

        $this->logger()->error($class.'.'.$message, $this->prepareContext($context));
    }

    /**
     * Prepare context information before logging.
     *
     * @param array $context
     *
     * @return array
     */
    private function prepareContext(array $context): array
    {
        return collect($context)
            ->map(function ($item) {
                if ($item instanceof Arrayable || method_exists($item, 'toArray')) {
                    return $item->toArray();
                }

                return $item;
            })
            ->toArray();
    }

    private function logger(): LoggerInterface
    {
        return Bot::getInstance()->get(LoggerInterface::class);
    }
}
