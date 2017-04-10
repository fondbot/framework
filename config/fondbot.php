<?php

return [

    /*
     * Here you specify channels which FondBot will use when receiving webhooks.
     * Read more about configuring: https://docs.fondbot.com/#/configuration?id=channels
     */
    'channels' => [
        // 'telegram-1' => [
        //      'driver' => 'telegram',
        //      'token' => 'token-for-telegram-1',
        // ],
        // 'facebook-1' => [
        //      'driver' => 'facebook',
        //      'page_token' => '',
        //      'verify_token' => '',
        //      'app_secret' => '',
        // ],
        // 'vk-communities-1' => [
        //      'driver' => 'vk-communities',
        //      'access_token' => '',
        //      'confirmation_token' => '',
        // ],
    ],

    /*
     * Here you define all intents.
     */
    'intents' => [
        // App\WeatherIntent::class
    ],

    /*
     * If no intent found based on your configuration this intent will be run.
     * You can send some helpful information in it.
     */
    'fallback_intent' => FondBot\Conversation\FallbackIntent::class,

];
