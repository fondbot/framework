<?php

declare(strict_types=1);

namespace FondBot\Contracts\Drivers\Message;

use FondBot\Contracts\Filesystem\File;

interface Attachment
{
    /**
     * Get attachment type.
     *
     * @return string
     */
    public function getType(): string;

    /**
     * Get path to the attachment.
     *
     * @return string
     */
    public function getPath(): string;

    /**
     * Get attachment contents.
     *
     * @return string
     */
    public function getContents(): string;

    /**
     * Get attachment as a file.
     *
     * @return File
     */
    public function getFile(): File;

    /**
     * Get all types.
     *
     * @return array
     */
    public static function possibleTypes(): array;
}
