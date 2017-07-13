<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Helpers;

use FondBot\Helpers\Str;
use FondBot\Tests\TestCase;

class StrTest extends TestCase
{
    public function testContains()
    {
        $string = 'FondBot is a great library for building chatbots.';

        $this->assertTrue(Str::contains($string, ['great', 'for', 'chatbots']));
        $this->assertFalse(Str::contains($string, ['bad', 'awful', 'human']));
    }

    public function testEndsWith()
    {
        $string = 'FondBot is a great library for building chatbots.';

        $this->assertTrue(Str::endsWith($string, ['building chatbots.', 'chatbots.']));
        $this->assertFalse(Str::endsWith($string, ['manufacturing cars.', 'stuff.']));
    }

    public function testRandom()
    {
        $this->assertSame(16, mb_strlen(Str::random()));
        $this->assertSame($length = random_int(1, 100), mb_strlen(Str::random($length)));
    }

    public function testLower()
    {
        $string = 'FondBot is a great library for building chatbots.';

        $this->assertSame('fondbot is a great library for building chatbots.', Str::lower($string));
    }
}
