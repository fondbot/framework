<?php

return [

    /*
     * Define all channels for your bot here.
     */
    'channels' => [
        'telegram' => [
            'driver' => 'telegram',
            'token' => '',
        ],
        'facebook' => [
            'driver' => 'facebook',
            'page_token' => '',
            'verify_token' => '',
            'app_secret' => '',
        ],
        'vk' => [
            'driver' => 'vk-communities',
            'access_token' => '',
            'confirmation_token' => '',
        ],
    ],

    /*
     * Here you define all intents which be used.
     *
     * Example: App\WeatherIntent::class
     */
    'intents' => [

    ],

    /*
     * Define fallback intent.
     *
     * If no intent found based on your configuration this intent will be run.
     * You can send some helpful information in it.
     */
    'fallback_intent' => FondBot\Conversation\Fallback\FallbackIntent::class,

];
