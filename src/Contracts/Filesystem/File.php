<?php

declare(strict_types=1);

namespace FondBot\Contracts\Filesystem;

interface File
{
    /**
     * Path to the file.
     *
     * @return string
     */
    public function getPath(): string;
}
