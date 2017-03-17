<?php

declare(strict_types=1);

namespace FondBot\Channels\Slack;

use GuzzleHttp\Client;
use FondBot\Channels\Driver;
use FondBot\Channels\Sender;
use FondBot\Channels\Message;
use FondBot\Channels\Request;
use FondBot\Channels\Receiver;
use FondBot\Conversation\Keyboard;
use GuzzleHttp\Exception\RequestException;
use FondBot\Contracts\Channels\WebhookInstallation;
use FondBot\Channels\Exceptions\InvalidChannelRequest;

class SlackDriver extends Driver implements WebhookInstallation
{
    private $guzzle;

    public function __construct(Client $guzzle)
    {
        $this->guzzle = $guzzle;
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

    }

    /**
     * Initialize webhook in the external service.
     *
     * @param string $url
     */
    public function installWebhook(string $url): void
    {

    }

    /**
     * Get message sender.
     *
     * @return Sender
     */
    public function getSender(): Sender
    {

    }

    /**
     * Get message received from sender.
     *
     * @return Message
     */
    public function getMessage(): Message
    {
        $text = $this->getRequest('message')['text'];

        return Message::create($text);
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

    private function getBaseUrl(): string
    {
        return  config('fondbot.slack.baseUrl');
    }
}
