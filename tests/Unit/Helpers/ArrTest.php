<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Helpers;

use FondBot\Helpers\Arr;
use FondBot\Tests\TestCase;

class ArrTest extends TestCase
{
    public function test_exists()
    {
        $array = ['name' => $this->faker()->name];

        $this->assertTrue(Arr::exists($array, 'name'));

        $collection = collect(['last_name' => $this->faker()->lastName]);

        $this->assertTrue(Arr::exists($collection, 'last_name'));
    }

    public function test_has()
    {
        $array = ['user' => ['name' => $this->faker()->name]];

        $this->assertFalse(Arr::has($array, []));
        $this->assertTrue(Arr::has($array, ['user']));
        $this->assertFalse(Arr::has($array, ['incorrect']));
        $this->assertTrue(Arr::has($array, ['user.name']));
        $this->assertFalse(Arr::has($array, ['user.last_name']));
    }

    public function test_get()
    {
        $array = ['user' => ['name' => $this->faker()->name]];
        $this->assertEquals($array['user'], Arr::get($array, 'user'));
        $this->assertEquals($array['user']['name'], Arr::get($array, 'user.name'));
        $this->assertEquals($array['user']['name'], Arr::get(collect($array), 'user.name'));
        $this->assertEquals($array, Arr::get($array, null));
    }
}
