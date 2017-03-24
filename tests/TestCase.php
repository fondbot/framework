<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Database\Eloquent\Model;
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

    /**
     * Create model and save in database.
     *
     * @param string $entity
     * @param array  $attributes
     *
     * @return \Illuminate\Database\Eloquent\Model|mixed
     */
    protected function factory(string $entity, array $attributes = []): Model
    {
        /** @var Model $entity */
        $entity = new $entity($attributes);

        $entity->save();

        return $entity;
    }

}
