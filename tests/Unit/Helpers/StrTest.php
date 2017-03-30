<?php

declare(strict_types=1);

namespace Tests\Unit\Helpers;

use Tests\TestCase;
use FondBot\Helpers\Str;

class StrTest extends TestCase
{
    public function test_endsWith()
    {
        $string = 'FondBot is a great library for building chatbots.';

        $this->assertTrue(Str::endsWith($string, ['building chatbots.', 'chatbots.']));
        $this->assertFalse(Str::endsWith($string, ['manufacturing cars.', 'stuff.']));
    }
}
