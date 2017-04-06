<?php

declare(strict_types=1);

namespace Tests\Unit\Queue;

use stdClass;
use Tests\TestCase;
use FondBot\Queue\SerializesForQueue;
use FondBot\Queue\SerializableForQueue;

class SerializesForQueueTest extends TestCase
{
    public function test_serializes_and_unserializes()
    {
        $class = new SerializesForQueueTraitTestClass;

        // Serialize and verify payload
        $serialized = $class->serialize(new SerializableTestClass((object) [
            'foo' => 'bar',
        ]));

        $json = json_decode($serialized, true);

        $this->assertSame(SerializableTestClass::class, $json['@type']);
        $this->assertArrayHasKey('data', $json);
        $this->assertSame(stdClass::class, $json['data']['@type']);
        $this->assertArrayHasKey('foo', $json['data']);
        $this->assertSame('bar', $json['data']['foo']);

        // Unserialize payload and verify result

        /** @var SerializableTestClass $unserialized */
        $unserialized = $class->unserialize($serialized);
        $this->assertInstanceOf(SerializableTestClass::class, $unserialized);

        $this->assertSame('bar', $unserialized->data->foo);
    }
}

class SerializesForQueueTraitTestClass
{
    use SerializesForQueue;

    public function __call($name, $arguments)
    {
        return $this->$name(...$arguments);
    }
}

class SerializableTestClass implements SerializableForQueue
{
    public $data;

    public function __construct(stdClass $data)
    {
        $this->data = $data;
    }
}
