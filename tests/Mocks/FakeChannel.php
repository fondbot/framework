<?php

declare(strict_types=1);

namespace FondBot\Tests\Mocks;

use FondBot\Channels\Channel;

class FakeChannel extends Channel
{
    public function __construct()
    {
        parent::__construct('foo', new FakeDriver);
    }
}
