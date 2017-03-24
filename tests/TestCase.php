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
     * Create mock instance.
     * Should be used for all classes. For models use ModelFactory.
     *
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
}
