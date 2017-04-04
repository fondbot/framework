<?php

return [

    /*
     * Define all channels for your bot here.
     */
    'channels' => [

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
    'fallback_intent' => FondBot\Conversation\FallbackIntent::class,

];
