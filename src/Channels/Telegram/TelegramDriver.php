<?php

declare(strict_types=1);

namespace FondBot\Channels\Telegram;

use FondBot\Contracts\Channels\ReceiverMessage;
use GuzzleHttp\Client;
use FondBot\Contracts\Channels\Driver;
use FondBot\Contracts\Channels\Sender;
use FondBot\Contracts\Channels\SenderMessage;
use FondBot\Contracts\Channels\Receiver;
use GuzzleHttp\Exception\RequestException;
use FondBot\Contracts\Conversation\Keyboard;
use FondBot\Contracts\Channels\WebhookInstallation;
use FondBot\Channels\Exceptions\InvalidChannelRequest;

class TelegramDriver extends Driver implements WebhookInstallation
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
        if (
            !$this->hasRequest('message') ||
            !$this->hasRequest('message.from')
        ) {
            throw new InvalidChannelRequest('Invalid payload');
        }
    }

    /**
     * Initialize webhook in the external service.
     *
     * @param string $url
     */
    public function installWebhook(string $url): void
    {
        $this->guzzle->post($this->getBaseUrl().'/setWebhook', [
            'form_params' => [
                'url' => $url,
            ],
        ]);
    }

    /**
     * Get message sender.
     *
     * @return Sender
     */
    public function getSender(): Sender
    {
        $from = $this->getRequest('message.from');

        return Sender::create(
            (string) $from['id'],
            $from['first_name'].' '.$from['last_name'],
            $from['username']
        );
    }

    /**
     * Get message received from sender.
     *
     * @return SenderMessage
     */
    public function getMessage(): SenderMessage
    {
        return new TelegramSenderMessage(
            $this->getParameter('token'),
            $this->getRequest('message')
        );
    }

    /**
     * Send reply to participant.
     *
     * @param Receiver $receiver
     * @param string $text
     * @param Keyboard|null $keyboard
     *
     * @return ReceiverMessage
     */
    public function sendMessage(Receiver $receiver, string $text, Keyboard $keyboard = null): ReceiverMessage
    {
        $message = new TelegramReceiverMessage($receiver, $text, $keyboard);

        try {
            $this->guzzle->post($this->getBaseUrl().'/sendMessage', [
                'form_params' => $message->toArray(),
            ]);
        } catch (RequestException $exception) {
            $this->error(get_class($exception), [$exception->getMessage()]);
        }

        return $message;
    }

    private function getBaseUrl(): string
    {
        return 'https://api.telegram.org/bot'.$this->getParameter('token');
    }
}
