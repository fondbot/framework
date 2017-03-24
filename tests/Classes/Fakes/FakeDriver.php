<?php

declare(strict_types=1);

namespace Tests\Classes\Fakes;

use Faker\Factory;
use Faker\Generator;
use FondBot\Contracts\Channels\Driver;
use FondBot\Contracts\Channels\Sender;
use FondBot\Contracts\Channels\Receiver;
use FondBot\Contracts\Conversation\Keyboard;
use FondBot\Contracts\Channels\SenderMessage;
use FondBot\Contracts\Channels\ReceiverMessage;
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
        return Channel::firstOrCreate([
            'driver' => self::class,
            'name' => $this->faker()->name,
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
     * @return Sender
     */
    public function getSender(): Sender
    {
        return $this->sender ?? $this->sender = Sender::create(
                $this->faker()->uuid,
                $this->faker()->name,
                $this->faker()->userName
            );
    }

    /**
     * Get message received from sender.
     *
     * @return SenderMessage
     */
    public function getMessage(): SenderMessage
    {
        return $this->message ?? $this->message = new FakeSenderMessage($this->faker()->text());
    }

    /**
     * Send reply to participant.
     *
     * @param Receiver      $receiver
     * @param string        $text
     * @param Keyboard|null $keyboard
     *
     * @return ReceiverMessage
     */
    public function sendMessage(Receiver $receiver, string $text, Keyboard $keyboard = null): ReceiverMessage
    {
        return new FakeReceiverMessage($receiver, $text, $keyboard);
    }

    private function faker(): Generator
    {
        return Factory::create();
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
