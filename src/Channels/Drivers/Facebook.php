<?php declare(strict_types = 1);

namespace FondBot\Channels\Drivers;

use FondBot\Channels\Abstracts\Driver;
use FondBot\Channels\Exceptions\InvalidChannelRequest;
use FondBot\Channels\Objects\Message;
use FondBot\Channels\Objects\Participant;
use FondBot\Conversation\Keyboard;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\HttpFoundation\Response;

class Facebook extends Driver
{
    public function init(): void
    {
        $this->http = new Client([
            'base_uri' => 'https://graph.facebook.com/v2.6/',
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
            'page_token',
            'verify_token',
        ];
    }

    private function defaultParameters()
    {
        return [
            'query' => [
                'access_token' => $this->parameter('page_token'),
            ],
        ];
    }

    /**
     * Route URI signature
     *
     * @return string
     */
    public function route(): string
    {
        return '/{token}';
    }

    /**
     * Verify incoming request data
     *
     * @throws InvalidChannelRequest
     */
    public function isInvalidRequest(): bool
    {
        $data = $this->request->json();

        if ($data === null) {
            $this->error('Request is empty');

            return true;
        }

        // Facebook check url for validity after submitting
        if ($this->request->has('hub_mode') && $this->request->has('hub_verify_token')) {
            return true;
        }

        return false;
    }

    public function handleInvalidRequest(): Response
    {
        if ($this->request->query('hub_mode') === 'subscribe'
            && $this->request->query('hub_verify_token') === $this->parameter('verify_token')
        ) {
            return response($this->request->query('hub_challenge'));
        }

        return response('OK');
    }

    public function participant(): Participant
    {
        $id = $this->request->json('entry.0.messaging.0.sender.id');

        $response = $this->http->get($id, $this->defaultParameters());

        $user = json_decode((string)$response->getBody());

        $username = "{$user->first_name}  {$user->last_name}";

        return Participant::create(
            $id,
            $username,
            $username
        );
    }

    public function message(): Message
    {
        $text = $this->request->json('entry.0.messaging.0.message.text');

        return Message::create($text);
    }

    public function reply(Participant $participant, Message $message, Keyboard $keyboard = null): void
    {
        $this->debug('reply', ['participant' => $participant, 'message' => $message, 'keyboard' => $keyboard]);

        $parameters = [
            'recipient' => [
                'id' => $participant->getIdentifier(),
            ],
            'message'   => [
                'text' => $message->getText(),
            ],
        ];

        if ($keyboard !== null) {
            foreach ($keyboard->buttons() as $button) {
                $parameters['message']['quick_replies'][] = [
                    'content_type' => 'text',
                    'title'        => $button->value(),
                    'payload'      => $button->value(),
                ];
            }
        }

        $this->debug('reply.request', $parameters);

        try {
            $this->http->post('me/messages', array_merge($this->defaultParameters(), [
                'form_params' => $parameters,
            ]));
        } catch (RequestException $exception) {
            $this->error(get_class($exception), [$exception->getMessage()]);
        }
    }

    /**
     * Initialize webhook in the external service
     *
     * @param string $url
     *
     * @throws InvalidChannelRequest
     */
    public function installWebhook(string $url): void
    {
        throw new InvalidChannelRequest('You can not set webhook by API call. '
            . 'Please visit https://developers.facebook.com/docs/messenger-platform/webhook-reference to see details.');
    }
}