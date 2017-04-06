<?php

declare(strict_types=1);

namespace FondBot\Drivers;

use FondBot\Queue\SerializableForQueue;

interface Command extends SerializableForQueue
{
}
