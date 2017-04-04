<?php

declare(strict_types=1);

namespace FondBot\Contracts\Filesystem;

class File
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
