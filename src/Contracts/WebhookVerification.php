<?php

declare(strict_types=1);

namespace FondBot\Contracts;

use Illuminate\Http\Request;

interface WebhookVerification
{
    /**
     * Determine if current request is verification.
     *
     * @param Request $request
     *
     * @return bool
     */
    public function isVerificationRequest(Request $request): bool;

    /**
     * Perform webhook verification.
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function verifyWebhook(Request $request);
}
