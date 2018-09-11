<?php

declare(strict_types=1);

namespace FondBot\Conversation\Activators;

use FondBot\Contracts\Activator;
use Illuminate\Support\Collection;
use FondBot\Events\MessageReceived;

class In implements Activator
{
    protected $values;

    /**
     * InArray constructor.
     *
     * @param array|Collection $values
     */
    protected function __construct($values)
    {
        if ($values instanceof Collection) {
            $values = $values->toArray();
        }

        $this->values = $values;
    }

    /**
     * @param array|Collection $values
     *
     * @return static
     */
    public static function make($values)
    {
        return new static($values);
    }

    /**
     * Result of matching activator.
     *
     * @param MessageReceived $message
     *
     * @return bool
     */
    public function matches(MessageReceived $message): bool
    {
        $haystack = $this->values;

        if ($haystack instanceof Collection) {
            $haystack = $haystack->toArray();
        }

        return in_array($message->getText(), $haystack, false);
    }
}
