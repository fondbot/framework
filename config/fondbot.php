<?php

return [

    'channels' => [
        'telegram' => [
            'driver' => 'telegram',
            'token' => env('TELEGRAM_TOKEN'),
        ],
    ],

    'intents' => [

    ],

    'fallbackIntent' => FondBot\Conversation\FallbackIntent::class,

];
