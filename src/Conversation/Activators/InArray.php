<?php

declare(strict_types=1);

namespace FondBot\Conversation\Activators;

use Illuminate\Support\Collection;
use FondBot\Contracts\Conversation\Activator;
use FondBot\Contracts\Drivers\ReceivedMessage;

class InArray implements Activator
{
    private $values;
    private $strict;

    /**
     * InArray constructor.
     *
     * @param array|Collection $values
     * @param bool             $strict
     */
    public function __construct($values, bool $strict = true)
    {
        $this->values = $values;
        $this->strict = $strict;
    }

    /**
     * Result of matching activator.
     *
     * @param ReceivedMessage $message
     *
     * @return bool
     */
    public function matches(ReceivedMessage $message): bool
    {
        $haystack = $this->values;

        if ($haystack instanceof Collection) {
            $haystack = $haystack->toArray();
        }

        return in_array($message->getText(), $haystack, $this->strict);
    }
}
