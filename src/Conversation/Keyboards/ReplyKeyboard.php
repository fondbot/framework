<?php

declare(strict_types=1);

namespace FondBot\Conversation\Keyboards;

use FondBot\Conversation\Keyboard;

class ReplyKeyboard extends Keyboard
{
    public static function create(array $buttons): ReplyKeyboard
    {
        $instance = new self;
        $instance->setType(self::TYPE_REPLY);
        $instance->setButtons($buttons);

        return $instance;
    }
}
