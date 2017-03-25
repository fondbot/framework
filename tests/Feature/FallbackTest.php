<?php

declare(strict_types=1);

namespace Tests\Feature;

use FondBot\Contracts\Database\Entities\Channel;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Classes\Fakes\FakeDriver;
use Tests\TestCase;

class FallbackTest extends TestCase
{
    use DatabaseMigrations;

    public function test()
    {
        $driver = $this->spy(FakeDriver::class);

        /** @var Channel $channel */
        $channel = $this->factory(Channel::class)->save();

        $response = $this->post('/fondbot/'.$channel->id);

        $response->assertStatus(200);
        $response->assertSee('OK');

        $driver->shouldHaveReceived('sendMessage');
    }

}
