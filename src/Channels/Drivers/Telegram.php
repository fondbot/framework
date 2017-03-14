<?php
declare(strict_types=1);

namespace FondBot\Channels\Drivers;

use FondBot\Channels\Abstracts\Driver;
use FondBot\Channels\Exceptions\InvalidChannelRequest;
use FondBot\Channels\Objects\Message;
use FondBot\Channels\Objects\Participant;
use FondBot\Channels\Request;
use FondBot\Conversation\Keyboard;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class Telegram extends Driver
{

    public function init(): void
    {
        // Set up http client
        if ($this->http === null) {
            $this->http = new Client([
                'base_uri' => 'https://api.telegram.org/bot' . $this->getParameter('token') . '/',
            ]);
        }
    }

    /**
     * Configuration parameters
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
     * Verify incoming request data
     *
     * @throws InvalidChannelRequest
     */
    public function verifyRequest(): void
    {
        if (!isset($this->request['message'], $this->request['message']['from'], $this->request['message']['text'])) {
            throw new InvalidChannelRequest('Invalid payload');
        }
    }

    /**
     * Initialize webhook in the external service
     *
     * @param string $url
     */
    public function installWebhook(string $url): void
    {
        $this->http->post('setWebhook', [
            'form_params' => [
                'url' => $url,
            ],
        ]);
    }

    public function getParticipant(): Participant
    {
        $from = $this->request['message']['from'];

        return Participant::create(
            (string)$from['id'],
            $from['first_name'] . ' ' . $from['last_name'],
            $from['username']
        );
    }

    public function getMessage(): Message
    {
        $text = $this->request['message']['text'];

        return Message::create($text);
    }

    public function reply(Participant $participant, Message $message, Keyboard $keyboard = null): void
    {
        $parameters = [
            'chat_id' => $participant->getIdentifier(),
            'text' => $message->getText(),
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
            $this->http->post('sendMessage', $request->toArray());
        } catch (RequestException $exception) {
            $this->error(get_class($exception), [$exception->getMessage()]);
        }
    }

}