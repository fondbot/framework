<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Application;

use FondBot\Tests\TestCase;
use Zend\Diactoros\Request;
use Zend\Diactoros\Response;
use FondBot\Application\Kernel;
use FondBot\Application\Controller;
use FondBot\Conversation\ConversationManager;

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

        $this->assertRegExp('/FondBot v([0-9]+)\.([0-9]+)\.([0-9]+)/', $response->getBody()->getContents());
    }

    public function test_webhook(): void
    {
        $conversationManager = $this->mock(ConversationManager::class);
        $request = new Request();
        $response = new Response('php://temp');
        $args = ['name' => 'foo'];

        $conversationManager->shouldReceive('handle')->andReturn('bar')->once();

        $controller = new Controller($this->kernel);
        $controller->webhook($request, $response, $args);

        $response->getBody()->rewind();

        $this->assertSame('bar', $response->getBody()->getContents());
    }
}
