<?php

namespace Tests;

use Faker\Generator;
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{

    protected function faker(): Generator
    {
        return \Faker\Factory::create();
    }

}
