<?php

declare(strict_types=1);

namespace FondBot\Filesystem;

use FondBot\Contracts\Filesystem\File as FileContract;

class File implements FileContract
{
    private $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     * Path to the file.
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }
}
