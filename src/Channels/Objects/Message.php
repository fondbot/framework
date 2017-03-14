<?php
declare(strict_types=1);

namespace FondBot\Channels\Objects;

class Message
{

    /** @var string */
    protected $text;

    public static function create(string $text): Message
    {
        $instance = new Message;
        $instance->setText($text);

        return $instance;
    }

    public function setText(string $text): void
    {
        $this->text = $text;
    }

    public function getText(): string
    {
        return $this->text;
    }
}
