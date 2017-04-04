<?php

declare(strict_types=1);

namespace FondBot\Contracts;

use FondBot\Contracts\Exceptions\FileExistsException;
use FondBot\Contracts\Exceptions\FileNotFoundException;

interface Filesystem
{
    /**
     * Read a file.
     *
     * @param string $path The path to the file.
     *
     * @throws FileNotFoundException
     *
     * @return string The file contents or false on failure.
     */
    public function read(string $path): string;

    /**
     * Write a new file.
     *
     * @param string $path     The path of the new file.
     * @param string $contents The file contents.
     *
     * @throws FileExistsException
     */
    public function write(string $path, string $contents): void;

    /**
     * Delete a file.
     *
     * @param string $path
     *
     * @throws FileNotFoundException
     */
    public function delete(string $path): void;
}
