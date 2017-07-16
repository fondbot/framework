<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Templates;

use FondBot\Tests\TestCase;
use FondBot\Templates\Attachment;

class AttachmentTest extends TestCase
{
    /**
     * @dataProvider types()
     *
     * @param string $type
     */
    public function test(string $type)
    {
        $attachment = (new Attachment)
            ->setType($type)
            ->setPath($path = $this->faker()->url)
            ->setMetadata($metadata = ['foo' => 'bar']);

        $this->assertSame($type, $attachment->getType());
        $this->assertSame($path, $attachment->getPath());
        $this->assertSame($metadata, $attachment->getMetadata());
    }

    public function testPossibleTypes()
    {
        $this->assertSame(collect($this->types())->flatten()->toArray(), Attachment::possibleTypes());
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
