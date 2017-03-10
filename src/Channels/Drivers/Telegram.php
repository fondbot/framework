<?php declare(strict_types=1);

namespace FondBot\Channels\Drivers;

use FondBot\Channels\Abstracts\Driver;
use FondBot\Channels\Exceptions\InvalidChannelRequest;
use FondBot\Channels\Objects\Message;
use FondBot\Channels\Objects\Participant;
use FondBot\Channels\Request;
use FondBot\Conversation\Abstracts\Keyboard;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class Telegram extends Driver
{

    public function init(): void
    {
        // Set up http client
        $this->http = new Client([
            'base_uri' => 'https://api.telegram.org/bot' . $this->parameter('token') . '/',
        ]);
    }

    /**
     * Configuration parameters
     *
     * @return array
     */
    public function config(): array
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
        $data = $this->request->json();

        if ($data === null) {
            throw new InvalidChannelRequest('Request is empty');
        }

        if (
            $this->request->json('message') === null ||
            $this->request->json('message.from') === null ||
            $this->request->json('message.text') === null
        ) {
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

    public function participant(): Participant
    {
        $from = $this->request->json('message.from');

        return Participant::create(
            (string)$from['id'],
            $from['first_name'] . ' ' . $from['last_name'],
            $from['username']
        );
    }

    public function message(): Message
    {
        $text = $this->request->json('message.text');

        return Message::create($text);
    }

    public function reply(Participant $participant, Message $message, Keyboard $keyboard = null): void
    {
        $this->debug('reply', ['participant' => $participant, 'message' => $message, 'keyboard' => $keyboard]);

        $parameters = [
            'chat_id' => $participant->getIdentifier(),
            'text' => $message->getText(),
        ];

        if ($keyboard !== null) {
            $buttons = [];

            foreach ($keyboard->buttons() as $button) {
                $buttons[] = ['text' => $button->value()];
            }

            $parameters['reply_markup'] = json_encode([
                'keyboard' => [$buttons],
                'resize_keyboard' => true,
            ]);
        }

        $request = Request::create($parameters);

        $this->debug('reply.request', $request->toArray());

        try {
            $this->http->post('sendMessage', $request->toArray());
        } catch (RequestException $exception) {
            $this->error(get_class($exception), [$exception->getMessage()]);
        }
    }

}