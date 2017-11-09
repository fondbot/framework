<?php

declare(strict_types=1);

namespace FondBot\Conversation;

use Illuminate\Support\Collection;
use FondBot\Conversation\Activators\Exact;
use FondBot\Conversation\Activators\Regex;
use FondBot\Conversation\Activators\InArray;
use FondBot\Conversation\Activators\Payload;
use FondBot\Conversation\Activators\Contains;
use FondBot\Conversation\Activators\Attachment;

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
     * Create "Regex" activator.
     *
     * @param string $value
     *
     * @return Regex
     */
    public static function regex(string $value): Regex
    {
        return new Regex($value);
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
     * Create "Attachment" activator.
     *
     * @param string|null $type
     *
     * @return Attachment
     */
    public static function attachment(string $type = null): Attachment
    {
        return new Attachment($type);
    }

    /**
     * Create "Payload" activator.
     *
     * @param string $value
     *
     * @return Payload
     */
    public static function payload(string $value): Payload
    {
        return new Payload($value);
    }
}
