<?php

namespace Tests;

use Faker\Generator;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Mockery;

abstract class TestCase extends BaseTestCase
{

    use CreatesApplication;

    protected function faker(): Generator
    {
        return \Faker\Factory::create();
    }

    /**
     * @param string $class
     * @return Mockery\MockInterface|Mockery\Mock|mixed
     */
    protected function mock(string $class)
    {
        return Mockery::mock($class);
    }

    /**
     * @param Mockery\MockInterface|Mockery\Mock $entity
     * @param string $attribute
     * @param $value
     */
    protected function shouldReturnAttribute(Mockery\MockInterface $entity, string $attribute, $value)
    {
        $entity->shouldReceive('getAttribute')->with($attribute)->andReturn($value);
    }

}
