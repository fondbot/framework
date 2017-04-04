<?php

declare(strict_types=1);

namespace FondBot\Drivers\ReceivedMessage;

use FondBot\Contracts\Arrayable;

class Attachment implements Arrayable
{
    public const TYPE_FILE = 'file';
    public const TYPE_IMAGE = 'image';
    public const TYPE_AUDIO = 'audio';
    public const TYPE_VIDEO = 'video';

    protected $type;
    protected $path;
    protected $contents;
    protected $guzzle;

    public function __construct(string $type, string $path)
    {
        $this->type = $type;
        $this->path = $path;
    }

    /**
     * Get attachment type.
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Get path to the attachment.
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Get all types.
     *
     * @return array
     */
    public static function possibleTypes(): array
    {
        return [
            static::TYPE_FILE,
            static::TYPE_IMAGE,
            static::TYPE_AUDIO,
            static::TYPE_VIDEO,
        ];
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'type' => $this->type,
            'path' => $this->path,
        ];
    }
}
