<?php

declare(strict_types=1);

namespace FondBot\Conversation;

use JsonSerializable;
use FondBot\Contracts\Arrayable;

interface Template extends Arrayable, JsonSerializable
{
}
