<?php

declare(strict_types=1);

namespace FondBot\Conversation\Traits;

use Illuminate\Support\Collection;
use FondBot\Conversation\Activators\Exact;
use FondBot\Conversation\Activators\InArray;
use FondBot\Conversation\Activators\Pattern;
use FondBot\Conversation\Activators\Contains;
use FondBot\Conversation\Activators\Activator;
use FondBot\Conversation\Activators\WithAttachment;

trait HasActivators
{
    /**
     * Intent activators.
     *
     * @return Activator[]
     */
    abstract public function activators(): array;

    /**
     * Create "Exact" activator.
     *
     * @param string $value
     *
     * @param bool   $caseSensitive
     *
     * @return Exact
     */
    protected function exact(string $value, bool $caseSensitive = false): Exact
    {
        return new Exact($value, $caseSensitive);
    }

    /**
     * @param string|array $needles
     *
     * @return Contains
     */
    protected function contains($needles): Contains
    {
        return new Contains($needles);
    }

    /**
     * Create "Pattern" activator.
     *
     * @param string $value
     *
     * @return Pattern
     */
    protected function pattern(string $value): Pattern
    {
        return new Pattern($value);
    }

    /**
     * Create "InArray" activator.
     *
     * @param array|Collection $values
     * @param bool             $strict
     *
     * @return InArray
     */
    protected function inArray($values, bool $strict = true): InArray
    {
        return new InArray($values, $strict);
    }

    /**
     * Create "WithAttachment" activator.
     *
     * @param string|null $type
     *
     * @return WithAttachment
     */
    protected function withAttachment(string $type = null): WithAttachment
    {
        return new WithAttachment($type);
    }
}
