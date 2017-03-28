<?php

declare(strict_types=1);

namespace FondBot\Channels\Viber;


use FondBot\Channels\Exceptions\InvalidChannelRequest;
use FondBot\Contracts\Channels\Driver;
use FondBot\Contracts\Channels\Extensions\WebhookInstallation;
use FondBot\Contracts\Channels\OutgoingMessage;
use FondBot\Contracts\Channels\ReceivedMessage;
use FondBot\Contracts\Channels\User;
use FondBot\Contracts\Conversation\Keyboard;

class ViberDriver extends Driver implements WebhookInstallation
{

    private $guzzle;

    public function __construct(Client $guzzle)
    {
        $this->guzzle = $guzzle;
    }

    /**
     * Initialize webhook in the external service.
     *
     * @param string $url
     */
    public function installWebhook(string $url): void
    {
        // TODO: Implement installWebhook() method.
    }

    /**
     * Configuration parameters.
     *
     * @return array
     */
    public function getConfig(): array
    {
        return [
            'token',
        ];
    }

    /**
     * Verify incoming request data.
     *
     * @throws InvalidChannelRequest
     */
    public function verifyRequest(): void
    {
        // TODO: Implement verifyRequest() method.
    }

    /**
     * Get user.
     *
     * @return User
     */
    public function getUser(): User
    {
        // TODO: Implement getUser() method.
    }

    /**
     * Get message received from sender.
     *
     * @return ReceivedMessage
     */
    public function getMessage(): ReceivedMessage
    {
        // TODO: Implement getMessage() method.
    }

    /**
     * Send reply to participant.
     *
     * @param User $sender
     * @param string $text
     * @param Keyboard|null $keyboard
     *
     * @return OutgoingMessage
     */
    public function sendMessage(User $sender, string $text, Keyboard $keyboard = null): OutgoingMessage
    {
        // TODO: Implement sendMessage() method.
    }
}