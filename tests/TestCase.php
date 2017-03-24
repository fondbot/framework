<?php

declare(strict_types=1);

namespace Tests;

use Mockery;
use Faker\Generator;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function faker(): Generator
    {
        return \Faker\Factory::create();
    }

    /**
     * @param string $class
     *
     * @return Mockery\MockInterface|Mockery\Mock|mixed
     */
    protected function mock(string $class)
    {
        $instance = Mockery::mock($class);

        $this->app->instance($class, $instance);

        return $instance;
    }

    /**
     * @param Mockery\MockInterface|Mockery\Mock $entity
     * @param string                             $attribute
     * @param                                    $value
     */
    protected function shouldReturnAttribute(Mockery\MockInterface $entity, string $attribute, $value)
    {
        $entity->shouldReceive('getAttribute')->with($attribute)->andReturn($value);
    }
}
