<?php

declare(strict_types=1);

namespace FondBot\Conversation;

use Illuminate\Support\Collection;
use FondBot\Conversation\Activators\Exact;
use FondBot\Conversation\Activators\InArray;
use FondBot\Conversation\Activators\Pattern;
use FondBot\Conversation\Activators\Contains;
use FondBot\Conversation\Activators\WithPayload;
use FondBot\Conversation\Activators\WithAttachment;

class Activator
{
    /**
     * Create "Exact" activator.
     *
     * @param string $value
     *
     * @param bool $caseSensitive
     *
     * @return Exact
     */
    public static function exact(string $value, bool $caseSensitive = false): Exact
    {
        return new Exact($value, $caseSensitive);
    }

    /**
     * @param string|array $needles
     *
     * @return Contains
     */
    public static function contains($needles): Contains
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
    public static function pattern(string $value): Pattern
    {
        return new Pattern($value);
    }

    /**
     * Create "InArray" activator.
     *
     * @param array|Collection $values
     * @param bool $strict
     *
     * @return InArray
     */
    public static function inArray($values, bool $strict = true): InArray
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
    public static function withAttachment(string $type = null): WithAttachment
    {
        return new WithAttachment($type);
    }

    /**
     * Create "WithPayload" activator.
     *
     * @param string $value
     *
     * @return WithPayload
     */
    public static function withPayload(string $value): WithPayload
    {
        return new WithPayload($value);
    }
}
