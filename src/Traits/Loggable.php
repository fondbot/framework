<?php

declare(strict_types=1);

namespace FondBot\Traits;

use FondBot\Contracts\LoggableArray;

trait Loggable
{
    protected function debug(string $message, array $context = []): void
    {
        if (config('app.debug') === true) {
            $class = get_class($this);
            logger($class.'.'.$message, $this->prepareContext($context));
        }
    }

    protected function error(string $message, array $context = []): void
    {
        $class = get_class($this);
        logger()->error($class.'.'.$message, $this->prepareContext($context));
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
                if ($item instanceof LoggableArray) {
                    return $item->toLoggableArray();
                }

                return $item;
            })
            ->toArray();
    }
}
