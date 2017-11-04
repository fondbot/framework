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
        $attachment = Attachment::create($type, $path = $this->faker()->url, $parameters = ['foo' => 'bar']);

        $this->assertSame($type, $attachment->getType());
        $this->assertSame($path, $attachment->getPath());
        $this->assertSame($parameters, $attachment->getParameters()->toArray());
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
