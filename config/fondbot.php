<?php

return [

    'channels' => [
        'telegram' => [
            'driver' => 'telegram',
            'token' => env('TELEGRAM_TOKEN'),
        ],
    ],

    'conversation' => [
        'intents' => [

        ],

        'fallbackIntent' => FondBot\Conversation\FallbackIntent::class,
    ],

];
