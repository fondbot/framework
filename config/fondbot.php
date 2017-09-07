<?php

declare(strict_types=1);

return [

    'channels' => [
        'telegram' => [
            'driver' => 'telegram',
            'token' => env('TELEGRAM_TOKEN'),
        ],
    ],

];
