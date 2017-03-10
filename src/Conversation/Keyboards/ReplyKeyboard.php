<?php declare(strict_types=1);

namespace FondBot\Conversation\Keyboards;

use FondBot\Conversation\Abstracts\Keyboard;

class ReplyKeyboard extends Keyboard
{

    public static function create(array $buttons): ReplyKeyboard
    {
        $instance = new ReplyKeyboard;
        $instance->setType(self::TYPE_REPLY);
        $instance->setButtons($buttons);

        return $instance;
    }

}