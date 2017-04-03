<?php

declare(strict_types=1);

namespace FondBot\Channels\Telegram;

use GuzzleHttp\Client;
use FondBot\Contracts\Channels\User;
use FondBot\Contracts\Channels\Driver;
use GuzzleHttp\Exception\RequestException;
use FondBot\Contracts\Conversation\Keyboard;
use FondBot\Contracts\Channels\OutgoingMessage;
use FondBot\Contracts\Channels\ReceivedMessage;
use FondBot\Channels\Exceptions\InvalidChannelRequest;
use FondBot\Contracts\Channels\Extensions\WebhookInstallation;

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
        if ($this->hasRequest('callback_query')) {
            return;
        }

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
     * @return User
     */
    public function getUser(): User
    {
        if ($this->hasRequest('callback_query')) {
            $from = $this->getRequest('callback_query.from');
        } else {
            $from = $this->getRequest('message.from');
        }

        return new TelegramUser($from);
    }

    /**
     * Get message received from sender.
     *
     * @return ReceivedMessage
     */
    public function getMessage(): ReceivedMessage
    {
        return new TelegramReceivedMessage(
            $this->guzzle,
            $this->getParameter('token'),
            $this->getRequest()
        );
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
        $message = new TelegramOutgoingMessage($sender, $text, $keyboard);

        try {
            $this->debug('sendMessage', $message->toArray());

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
