<?php

declare(strict_types=1);

namespace FondBot\Contracts\Channels;

interface WebhookInstallation
{
    /**
     * Initialize webhook in the external service.
     *
     * @param string $url
     */
    public function installWebhook(string $url): void;
}
