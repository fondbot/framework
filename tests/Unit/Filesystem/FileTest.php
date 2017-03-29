<?php

declare(strict_types=1);

namespace Tests\Unit\Filesystem;

use Tests\TestCase;
use FondBot\Filesystem\File;

class FileTest extends TestCase
{
    public function test_getPath()
    {
        $path = $this->faker()->imageUrl();
        $file = new File($path);

        $this->assertSame($path, $file->getPath());
    }
}
