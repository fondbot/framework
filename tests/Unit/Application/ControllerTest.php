<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Application;

use FondBot\Tests\TestCase;
use Zend\Diactoros\Request;
use Zend\Diactoros\Response;
use FondBot\Channels\Channel;
use FondBot\Application\Kernel;
use FondBot\Application\Controller;
use FondBot\Channels\ChannelManager;

class ControllerTest extends TestCase
{
    public function test_index(): void
    {
        $kernel = $this->mock(Kernel::class);
        $request = new Request();
        $response = new Response('php://temp');

        $controller = new Controller($kernel);
        $controller->index($request, $response);

        $response->getBody()->rewind();

        $this->assertSame('FondBot v1.0.0', $response->getBody()->getContents());
    }

    public function test_webhook(): void
    {
        $kernel = $this->mock(Kernel::class);
        $channelManager = $this->mock(ChannelManager::class);
        $channel = $this->mock(Channel::class);
        $request = new Request();
        $response = new Response('php://temp');
        $args = ['name' => 'foo'];

        $kernel->shouldReceive('resolve')->with(ChannelManager::class)->andReturn($channelManager)->once();
        $channelManager->shouldReceive('create')->with('foo')->andReturn($channel)->once();
        $kernel->shouldReceive('process')->once()->andReturn('bar');

        $controller = new Controller($kernel);
        $controller->webhook($request, $response, $args);

        $response->getBody()->rewind();

        $this->assertSame('bar', $response->getBody()->getContents());
    }
}
