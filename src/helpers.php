<?php

declare(strict_types=1);

if (!function_exists('kernel')) {
    /**
     * Get kernel instance.
     *
     * @return \FondBot\FondBot
     */
    function kernel()
    {
        return resolve(FondBot\FondBot::class);
    }
}

if (!function_exists('context')) {
    /**
     * Get context.
     *
     * @param string|null $key
     *
     * @param mixed|null  $default
     *
     * @return \FondBot\Conversation\Context|mixed
     */
    function context(string $key = null, $default = null)
    {
        $conversation = resolve(\FondBot\Conversation\ConversationManager::class);

        $context = $conversation->getContext();

        if ($key === null) {
            return $context;
        }

        return optional($context)->getItem($key, $default);
    }
}
