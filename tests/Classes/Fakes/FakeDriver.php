<?php

declare(strict_types=1);

namespace Tests\Classes\Fakes;

use Tests\Factory;
use FondBot\Contracts\Channels\User;
use FondBot\Contracts\Channels\Driver;
use FondBot\Contracts\Conversation\Keyboard;
use FondBot\Contracts\Channels\OutgoingMessage;
use FondBot\Contracts\Channels\ReceivedMessage;
use FondBot\Contracts\Database\Entities\Channel;
use FondBot\Channels\Exceptions\InvalidChannelRequest;
use FondBot\Contracts\Channels\Extensions\WebhookVerification;

class FakeDriver extends Driver implements WebhookVerification
{
    private $sender;
    private $message;

    /**
     * Get channel.
     *
     * @return Channel|\Illuminate\Database\Eloquent\Model
     */
    public function getChannel(): Channel
    {
        return (new Factory(Channel::class))->create([
            'driver' => self::class,
            'parameters' => $this->getConfig(),
        ]);
    }

    /**
     * Configuration parameters.
     *
     * @return array
     */
    public function getConfig(): array
    {
        return [];
    }

    /**
     * Verify incoming request data.
     *
     * @throws InvalidChannelRequest
     */
    public function verifyRequest(): void
    {
    }

    /**
     * Get message sender.
     *
     * @return User
     */
    public function getUser(): User
    {
        return $this->sender ?? $this->sender = (new Factory())->sender();
    }

    /**
     * Get message received from sender.
     *
     * @return ReceivedMessage
     */
    public function getMessage(): ReceivedMessage
    {
        return $this->message ?? $this->message = (new Factory())->senderMessage();
    }

    /**
     * Send reply to participant.
     *
     * @param User          $sender
     * @param string        $text
     * @param Keyboard|null $keyboard
     *
     * @return OutgoingMessage
     */
    public function sendMessage(User $sender, string $text, Keyboard $keyboard = null): OutgoingMessage
    {
        return new FakeOutgoingMessage($sender, $text, $keyboard);
    }

    /**
     * Whether current request type is verification.
     *
     * @return bool
     */
    public function isVerificationRequest(): bool
    {
        return $this->hasRequest('verification');
    }

    /**
     * Run webhook verification and respond if required.
     *
     * @return mixed
     */
    public function verifyWebhook()
    {
        return $this->getRequest('verification');
    }
}
