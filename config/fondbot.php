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
        'slack' => [
            'driver' => 'slack',
            'token' => 'xoxb-157566427844-tXQpGvWsMtHXELTNz37kfpU3',
        ]
    ],

    /*
     * Here you define all stories which be used.
     *
     * Example: App\Bot\StartStory::class
     */
    'stories' => [

    ],

    /*
     * Define fallback story.
     *
     * If no story found based on your configuration this story will be run.
     * You can send some helpful information in it.
     */
    'fallback_story' => FondBot\Conversation\Fallback\FallbackStory::class,

];
