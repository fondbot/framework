<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Drivers\ReceivedMessage;

use FondBot\Tests\TestCase;
use FondBot\Drivers\ReceivedMessage\Attachment;

class AttachmentTest extends TestCase
{
    /**
     * @dataProvider types()
     *
     * @param string $type
     */
    public function test(string $type)
    {
        $attachment = new Attachment($type, $url = $this->faker()->url);

        $array = ['type' => $type, 'path' => $url];

        $this->assertSame($type, $attachment->getType());
        $this->assertSame($url, $attachment->getPath());
        $this->assertSame($array, $attachment->toArray());
    }

    public function test_possibleTypes()
    {
        $expected = ['file', 'image', 'audio', 'video'];
        $this->assertSame($expected, Attachment::possibleTypes());
    }

    public function types()
    {
        return [
            ['file'],
            ['image'],
            ['audio'],
            ['video'],
        ];
    }
}
