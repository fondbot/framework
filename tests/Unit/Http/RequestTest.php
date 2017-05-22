<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Http;

use FondBot\Http\Request;
use FondBot\Tests\TestCase;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Request as ZendRequest;

class RequestTest extends TestCase
{
    public function test(): void
    {
        $parameters = ['foo' => 'bar'];
        $headers = ['x' => 'y'];

        $request = new Request($parameters, $headers);

        $this->assertSame($parameters, $request->getParameters());
        $this->assertSame('bar', $request->getParameter('foo'));
        $this->assertNull($request->getParameter('x'));
        $this->assertSame('z', $request->getParameter('x', 'z'));
        $this->assertTrue($request->hasParameters('foo'));
        $this->assertFalse($request->hasParameters(['foo', 'bar']));
        $this->assertSame($headers, $request->getHeaders());
        $this->assertSame('y', $request->getHeader('x'));
        $this->assertNull($request->getHeader('foo'));

        // Test create from message
        $stream = fopen('php://memory', 'rb+');
        fwrite($stream, json_encode($parameters));
        rewind($stream);

        $message = new ZendRequest('/', 'POST', $stream);
        $request = Request::fromMessage($message);

        $this->assertSame($parameters, $request->getParameters());

        // Test create from message with empty body
        $message = new ZendRequest('/', 'POST');
        $request = Request::fromMessage($message);

        $this->assertSame([], $request->getParameters());

        // Test create from ServerRequest
        $message = new ServerRequest([], [], null, null, 'php://input', ['x' => 'y'], [], ['foo' => 'bar']);
        $request = Request::fromMessage($message);

        $this->assertSame(['foo' => 'bar'], $request->getParameters());
        $this->assertSame(['x' => ['y']], $request->getHeaders());
    }
}
