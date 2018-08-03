<?php

declare(strict_types=1);

namespace FondBot\Events;

use FondBot\Contracts\Event;

class Unknown implements Event
{
    public function toResponse($request)
    {
        return [];
    }
}
