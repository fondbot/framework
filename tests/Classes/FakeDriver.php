<?php

declare(strict_types=1);

namespace Tests\Classes;

use Faker\Factory;
use Faker\Generator;
use FondBot\Channels\Exceptions\InvalidChannelRequest;
use FondBot\Contracts\Channels\Driver;
use FondBot\Contracts\Channels\Message;
use FondBot\Contracts\Channels\Receiver;
use FondBot\Contracts\Channels\Sender;
use FondBot\Contracts\Database\Entities\Channel;
use FondBot\Conversation\Keyboard;

class FakeDriver extends Driver
{
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
        return Sender::create(
            $this->faker()->uuid,
            $this->faker()->name,
            $this->faker()->userName
        );
    }

    /**
     * Get message received from sender.
     *
     * @return Message
     */
    public function getMessage(): Message
    {
        return Message::create($this->faker()->text);
    }

    /**
     * Send reply to participant.
     *
     * @param Receiver $receiver
     * @param string $text
     * @param Keyboard|null $keyboard
     */
    public function sendMessage(Receiver $receiver, string $text, Keyboard $keyboard = null): void
    {
    }

    private function faker(): Generator
    {
        return Factory::create();
    }
}
