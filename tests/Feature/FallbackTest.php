<?php

declare(strict_types=1);

namespace Tests\Feature;

use FondBot\Channels\DriverManager;
use Tests\TestCase;
use Tests\Classes\Fakes\FakeDriver;

class FallbackTest extends TestCase
{
    public function test()
    {
        $driver = $this->spy(FakeDriver::class);

        $this->app[DriverManager::class]->add('fake', $driver);

        config([
            'fondbot' => [
                'channels' => [
                    'test' => [
                        'driver' => 'fake',
                    ],
                ],
            ],
        ]);

        $response = $this->post('/fondbot/test');

        $response->assertStatus(200);
        $response->assertSee('OK');

        $driver->shouldHaveReceived('sendMessage');
    }
}
