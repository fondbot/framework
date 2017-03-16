<?php

declare(strict_types=1);

namespace FondBot\Contracts\Channels;

interface WebhookVerification
{
    /**
     * Run webhook verification and respond if required.
     *
     * @return mixed
     */
    public function verifyWebhook();
}
