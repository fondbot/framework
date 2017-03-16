<?php

declare(strict_types=1);

namespace FondBot\Channels\Telegram;

use FondBot\Channels\Driver;
use FondBot\Channels\Exceptions\InvalidChannelRequest;
use FondBot\Channels\Message;
use FondBot\Channels\Receiver;
use FondBot\Channels\Request;
use FondBot\Channels\Sender;
use FondBot\Conversation\Keyboard;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class TelegramDriver extends Driver
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
            $this->getRequest('message') === null ||
            !isset(
                $this->getRequest('message')['from'],
                $this->getRequest('message')['text']
            )
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
        $from = $this->getRequest('message')['from'];

        return Sender::create(
            (string)$from['id'],
            $from['first_name'].' '.$from['last_name'],
            $from['username']
        );
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
        $parameters = [
            'chat_id' => $receiver->getIdentifier(),
            'text' => $text,
        ];

        if ($keyboard !== null) {
            $buttons = [];

            foreach ($keyboard->getButtons() as $button) {
                $buttons[] = ['text' => $button->getValue()];
            }

            $parameters['reply_markup'] = json_encode([
                'keyboard' => [$buttons],
                'resize_keyboard' => true,
            ]);
        }

        $request = Request::create($parameters);

        try {
            $this->guzzle->post($this->getBaseUrl().'/sendMessage', $request->toArray());
        } catch (RequestException $exception) {
            $this->error(get_class($exception), [$exception->getMessage()]);
        }
    }

    private function getBaseUrl(): string
    {
        return 'https://api.telegram.org/bot'.$this->getParameter('token');
    }
}
