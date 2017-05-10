<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Helpers;

use FondBot\Helpers\Arr;
use FondBot\Tests\TestCase;

class ArrTest extends TestCase
{
    public function test_exists(): void
    {
        $array = ['name' => $this->faker()->name];

        $this->assertTrue(Arr::exists($array, 'name'));

        $collection = collect(['last_name' => $this->faker()->lastName]);

        $this->assertTrue(Arr::exists($collection, 'last_name'));
    }

    public function test_has(): void
    {
        $array = ['user' => ['name' => $this->faker()->name]];

        $this->assertFalse(Arr::has($array, []));
        $this->assertTrue(Arr::has($array, ['user']));
        $this->assertFalse(Arr::has($array, ['incorrect']));
        $this->assertTrue(Arr::has($array, ['user.name']));
        $this->assertFalse(Arr::has($array, ['user.last_name']));
    }

    public function test_get(): void
    {
        $array = ['user' => ['name' => $this->faker()->name]];
        $this->assertEquals($array['user'], Arr::get($array, 'user'));
        $this->assertEquals($array['user']['name'], Arr::get($array, 'user.name'));
        $this->assertEquals($array['user']['name'], Arr::get(collect($array), 'user.name'));
        $this->assertEquals($array, Arr::get($array, null));
    }

    public function test_set(): void
    {
        $array = ['user' => ['name' => $this->faker()->name]];

        Arr::set($array, 'user.name', 'Vladimir');

        $this->assertSame(['user' => ['name' => 'Vladimir']], $array);

        Arr::set($array, 'user.data.id', $uuid = $this->faker()->uuid);

        $this->assertEquals(['user' => ['data' => ['id' => $uuid], 'name' => 'Vladimir']], $array);
    }

    public function test_forget(): void
    {
        $array = ['user' => ['name' => $this->faker()->name]];

        Arr::forget($array, []);
        Arr::forget($array, ['user']);

        $this->assertSame([], $array);

        $array = ['user' => ['name' => $this->faker()->name]];

        Arr::forget($array, 'user.name');

        $this->assertSame(['user' => []], $array);

        $array = ['user' => ['info' => ['id' => $this->faker()->uuid]], 'foo' => 'bar'];

        Arr::forget($array, ['user.info.id', 'user.data.name']);
        $this->assertEquals(['user' => ['info' => []], 'foo' => 'bar'], $array);
    }
}
