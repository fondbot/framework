<?php

declare(strict_types=1);

namespace FondBot\Templates;

class Attachment
{
    public const TYPE_FILE = 'file';
    public const TYPE_IMAGE = 'image';
    public const TYPE_AUDIO = 'audio';
    public const TYPE_VIDEO = 'video';

    private $type;
    private $path;
    private $metadata;

    /**
     * Get type.
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Set type.
     *
     * @param string $type
     *
     * @return Attachment
     */
    public function setType(string $type): Attachment
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get path.
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Set path.
     *
     * @param string $path
     *
     * @return Attachment
     */
    public function setPath(string $path): Attachment
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get metadata.
     *
     * @return array
     */
    public function getMetadata(): array
    {
        return $this->metadata;
    }

    /**
     * Set metadata.
     *
     * @param array $metadata
     *
     * @return Attachment
     */
    public function setMetadata(array $metadata): Attachment
    {
        $this->metadata = $metadata;

        return $this;
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
}
