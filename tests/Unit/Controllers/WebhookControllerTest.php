<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Controllers;

use FondBot\Channels\Channel;
use FondBot\Channels\ChannelManager;
use FondBot\Controllers\WebhookController;
use FondBot\Foundation\RequestHandler;
use FondBot\Tests\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;

class WebhookControllerTest extends TestCase
{
    public function test_run_not_result(): void
    {
        $name = $this->faker()->word;
        $channel = $this->mock(Channel::class);
        $channelManger        = $this->mock(ChannelManager::class);
        $requestHandler = $this->mock(RequestHandler::class);
        $request  = $this->mock(ServerRequestInterface::class);
        $response = $this->mock(ResponseInterface::class);


        $channelManger->shouldReceive('create')->once()->with($name)->andReturn($channel);
        $requestHandler->shouldReceive('handle')->once()->with($channel, $request)->andReturn(null);
        $response->shouldReceive('withStatus')->once()->with(200)->andReturnSelf();
        $webhook = new WebhookController($channelManger, $requestHandler);
        $webhook->run($request, $response, ['name' => $name]);
    }

    public function test_run_exist_result()
    {
        $name = $this->faker()->word;
        $result = $this->faker()->randomLetter;
        $channel = $this->mock(Channel::class);
        $channelManger        = $this->mock(ChannelManager::class);
        $requestHandler = $this->mock(RequestHandler::class);
        $request  = $this->mock(ServerRequestInterface::class);
        $response = $this->mock(ResponseInterface::class);
        $stream = $this->mock(StreamInterface::class);

        $channelManger->shouldReceive('create')->once()->with($name)->andReturn($channel);
        $requestHandler->shouldReceive('handle')->once()->with($channel, $request)
                        ->andReturn($result);
        $response->shouldReceive('getBody')->once()->andReturn($stream);
        $stream->shouldReceive('write')->once()->with($result);
        $response->shouldReceive('withStatus')->once()->with(200)->andReturnSelf();
        $webhook = new WebhookController($channelManger, $requestHandler);
        $webhook->run($request, $response, ['name' => $name]);
    }
}
