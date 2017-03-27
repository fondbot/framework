<?php

declare(strict_types=1);

namespace Tests\Unit\Channels;

use FondBot\Channels\Channel;
use Tests\TestCase;
use Tests\Classes\Fakes\FakeDriver;
use FondBot\Channels\DriverManager;

/**
 * @property DriverManager manager
 */
class DriverManagerTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->manager = new DriverManager();
        $this->manager->add('fake', new FakeDriver());
    }

    public function test_get()
    {
        $channel = new Channel('test', 'fake', ['token' => str_random()]);

        $driver = $this->manager->get($channel);

        $this->assertInstanceOf(FakeDriver::class, $driver);
    }
}
