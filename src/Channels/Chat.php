<?php

declare(strict_types=1);

namespace FondBot\Channels;

class Chat
{
    public const TYPE_PRIVATE = 'private';
    public const TYPE_GROUP = 'group';

    private $id;
    private $title;
    private $type = self::TYPE_PRIVATE;
    private $data = [];

    protected function __construct(string $id)
    {
        $this->id = $id;
    }

    public static function make(string $id)
    {
        return new static($id);
    }

    public function title(string $title)
    {
        $this->title = $title;

        return $this;
    }

    public function type(string $type)
    {
        $this->type = $type;

        return $this;
    }

    public function data(array $data)
    {
        $this->data = $data;

        return $this;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getData(string $key = null): array
    {
        return $key === null ? $this->data : array_get($this->data, $key);
    }
}
