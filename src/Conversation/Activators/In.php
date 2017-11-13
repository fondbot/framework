<?php

declare(strict_types=1);

namespace FondBot\Conversation\Activators;

use Illuminate\Support\Collection;
use FondBot\Events\MessageReceived;
use FondBot\Contracts\Conversation\Activator;

class In implements Activator
{
    protected $values;

    /**
     * InArray constructor.
     *
     * @param array|Collection $values
     */
    public function __construct($values)
    {
        if ($values instanceof Collection) {
            $values = $values->toArray();
        }

        $this->values = $values;
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
