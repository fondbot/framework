<?php

return [

    /*
     * Namespace where your Stories and Interactions will be resolved from.
     * This namespace should be related to your base application namespace.
     */
    'namespace' => 'Bot',

    /**
     * Here you define all stories which be used.
     *
     * Example: App\Bot\StartStory::class
     */
    'stories' => [

    ],

    /**
     * Define fallback story.
     *
     * If no story found based on your configuration this story will be run.
     * You can send some helpful information in it.
     */
    'fallback_story' => FondBot\Conversation\Fallback\FallbackStory::class,

];
