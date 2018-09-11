<?php

declare(strict_types=1);

use Carbon\Carbon;

return [

    /*
    |--------------------------------------------------------------------------
    | FondBot Channels
    |--------------------------------------------------------------------------
    |
    | Here you may configure channels that will be used in your application.
    | A default configuration has been added for each official drivers.
    | You are free to add as many channels as you need.
    |
    */

    'channels' => [

        // 'telegram' => [
        //    'driver' => 'telegram',
        //    'token' => env('TELEGRAM_TOKEN'),
        // ],

        // 'vk' => [
        //    'driver' => 'vk',
        //    'access_token' => env('VK_ACCESS_TOKEN'),
        //    'confirmation_token' => env('VK_CONFIRMATION_TOKEN'),
        //    'secret_key' => env('VK_SECRET_KEY'),
        //    'group_id' => env('VK_GROUP_ID'),
        // ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Intents Path
    |--------------------------------------------------------------------------
    |
    | Here you may specify an array of paths that should be checked for your intents.
    |
    */

    'intents_path' => [
        app_path('Intents'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Fallback Intent
    |--------------------------------------------------------------------------
    |
    | When none of the intents activated, fallback intent will run.
    |
    */

    'fallback_intent' => FondBot\Conversation\FallbackIntent::class,

    /*
    |--------------------------------------------------------------------------
    | Context
    |--------------------------------------------------------------------------
    |
    | Time to live in seconds for conversation context.
    | By default, the value is set to 1 day.
    |
    */
    'context_ttl' => Carbon::SECONDS_PER_MINUTE * Carbon::MINUTES_PER_HOUR * Carbon::HOURS_PER_DAY,

];
