<?php

declare(strict_types=1);

namespace Fondbot\Channels\Facebook;

use GuzzleHttp\Client;
use FondBot\Channels\Sender;
use FondBot\Channels\Message;
use FondBot\Channels\Receiver;
use FondBot\Conversation\Keyboard;
use FondBot\Contracts\Channels\Driver;
use GuzzleHttp\Exception\RequestException;
use FondBot\Contracts\Channels\WebhookVerification;
use FondBot\Channels\Exceptions\InvalidChannelRequest;

class FacebookDriver extends Driver implements WebhookVerification
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
            'page_token',
            'verify_token',
            'app_secret',
        ];
    }

    /**
     * Verify incoming request data.
     *
     * @throws InvalidChannelRequest
     */
    public function verifyRequest(): void
    {
        if (!array_has($this->getRequest('entry'), '0.messaging.0.sender.id')
            || !array_has($this->getRequest('entry'), '0.messaging.0.message.text')
        ) {
            throw new InvalidChannelRequest('Invalid payload');
        }
    }

    /**
     * Get message sender.
     * @return Sender
     * @throws InvalidChannelRequest
     */
    public function getSender(): Sender
    {
        // todo When bot can process with multiple messages, rewrite to looping
        $id = array_get($this->getRequest('entry'), '0.messaging.0.sender.id');

        try {
            $response = $this->guzzle->get($this->getBaseUrl().$id, $this->getDefaultRequestParameters());
        } catch (RequestException $exception) {
            $this->error(get_class($exception), [$exception->getMessage()]);

            throw new InvalidChannelRequest('Can not get user profile', 0, $exception);
        }

        $user = json_decode((string) $response->getBody());

        $username = "{$user->first_name} {$user->last_name}";

        return Sender::create(
            (string) $id,
            $username,
            $username
        );
    }

    /**
     * Get message received from sender.
     *
     * @return Message
     */
    public function getMessage(): Message
    {
        $text = array_get($this->getRequest('entry'), '0.messaging.0.message.text');

        return Message::create($text);
    }

    /**
     * Send reply to participant.
     *
     * @param Receiver      $receiver
     * @param string        $text
     * @param Keyboard|null $keyboard
     */
    public function sendMessage(Receiver $receiver, string $text, Keyboard $keyboard = null): void
    {
        $parameters = [
            'recipient' => [
                'id' => $receiver->getIdentifier(),
            ],
            'message'   => [
                'text' => $text,
            ],
        ];

        if ($keyboard !== null) {
            foreach ($keyboard->getButtons() as $button) {
                $parameters['message']['quick_replies'][] = [
                    'content_type' => 'text',
                    'title'        => $button->getValue(),
                    'payload'      => $button->getValue(),
                ];
            }
        }

        try {
            $this->guzzle->post($this->getBaseUrl().'me/messages',
                $this->getDefaultRequestParameters() + ['form_params' => $parameters]);
        } catch (RequestException $exception) {
            $this->error(get_class($exception), [$exception->getMessage()]);
        }
    }

    /**
     * Whether current request type is verification.
     *
     * @return bool
     */
    public function isVerificationRequest(): bool
    {
        return $this->getRequest('hub_mode') === 'subscribe'
            && $this->getRequest('hub_verify_token')
            || $this->getParameter('app_secret');
    }

    /**
     * Run webhook verification and respond if required.
     *
     * @return mixed
     */
    public function verifyWebhook()
    {
        if ($this->getRequest('hub_mode') === 'subscribe'
            && $this->getRequest('hub_verify_token') === $this->getParameter('verify_token')
        ) {
            //todo If verify token does not match need Exception or log error, for this need VerificationManager and add method like hasRequest
            return $this->getRequest('hub_challenge');
        }

        if ($this->getParameter('app_secret')) {
            //todo for this testing need add setter and getter headers and add modify method getRequest to return all request data
            return hash_equals(request()->header('X_HUB_SIGNATURE', ''),
                'sha1='.hash_hmac('sha1', request()->getContent(), $this->getParameter('app_secret')));
        }

        return 'OK';
    }

    private function getBaseUrl(): string
    {
        return 'https://graph.facebook.com/v2.6/';
    }

    private function getDefaultRequestParameters(): array
    {
        return [
            'query' => [
                'access_token' => $this->getParameter('page_token'),
            ],
        ];
    }
}
