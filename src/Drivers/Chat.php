<?php

declare(strict_types=1);

namespace FondBot\Drivers;

class Chat
{
    public const TYPE_PRIVATE = 'private';
    public const TYPE_GROUP = 'group';

    private $id;
    private $title;
    private $type;

    public function __construct(string $id, string $title, string $type = self::TYPE_PRIVATE)
    {
        $this->id = $id;
        $this->title = $title;
        $this->type = $type;
    }

    /**
     * Get chat identifier.
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Get title of the chat.
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Get type of the chat.
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
}
