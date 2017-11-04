<?php

declare(strict_types=1);

namespace FondBot\Templates;

use Illuminate\Support\Collection;

class Attachment
{
    public const TYPE_FILE = 'file';
    public const TYPE_IMAGE = 'image';
    public const TYPE_AUDIO = 'audio';
    public const TYPE_VIDEO = 'video';

    private $type;
    private $path;
    private $parameters;

    public function __construct(string $type, string $path, array $parameters = [])
    {
        $this->type = $type;
        $this->path = $path;
        $this->parameters = collect($parameters);
    }

    public static function create(string $type, string $path, array $parameters = [])
    {
        return new static($type, $path, $parameters);
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): Attachment
    {
        $this->type = $type;

        return $this;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): Attachment
    {
        $this->path = $path;

        return $this;
    }

    public function getParameters(): Collection
    {
        return $this->parameters;
    }

    public function setParameters(array $parameters): Attachment
    {
        $this->parameters = collect($parameters);

        return $this;
    }

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
