<?php

declare(strict_types=1);

namespace FondBot\Channels\Facebook;

use GuzzleHttp\Client;
use FondBot\Conversation\Keyboard;
use FondBot\Contracts\Channels\Driver;
use FondBot\Contracts\Channels\Sender;
use FondBot\Contracts\Channels\Message;
use FondBot\Contracts\Channels\Receiver;
use GuzzleHttp\Exception\RequestException;
use FondBot\Contracts\Channels\Message\Location;
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
        $this->verifySignature();

        if (!$this->hasRequest('entry.0.messaging.0.sender.id')
            || !$this->hasRequest('entry.0.messaging.0.message.text')
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
        $id = $this->getRequest('entry.0.messaging.0.sender.id');

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
        $location = null;
        $text = $this->getRequest('entry.0.messaging.0.message.text') ?: '';

        if ($attachment = $this->getRequestAttachments('location')) {
            $location = Location::create(
                $attachment['payload']['coordinates']['lat'],
                $attachment['payload']['coordinates']['long']
            );
        }

        return Message::create($text, $location);
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
            'recipient' => [
                'id' => $receiver->getIdentifier(),
            ],
            'message' => [
                'text' => $text,
            ],
        ];

        if ($keyboard !== null) {
            foreach ($keyboard->getButtons() as $button) {
                $parameters['message']['quick_replies'][] = [
                    'content_type' => 'text',
                    'title' => $button->getValue(),
                    'payload' => $button->getValue(),
                ];
            }
        }

        try {
            $this->guzzle->post(
                $this->getBaseUrl().'me/messages',
                $this->getDefaultRequestParameters() + ['form_params' => $parameters]
            );
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
        return $this->getRequest('hub_mode') === 'subscribe' && $this->hasRequest('hub_verify_token');
    }

    /**
     * Run webhook verification and respond if required.
     * @return mixed
     * @throws InvalidChannelRequest
     */
    public function verifyWebhook()
    {
        if ($this->getRequest('hub_verify_token') === $this->getParameter('verify_token')) {
            return $this->getRequest('hub_challenge');
        }

        throw new InvalidChannelRequest('Invalid verify token');
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

    private function verifySignature(): void
    {
        if (!$secret = $this->getParameter('app_secret')) {
            // If app secret non set, just skip this check
            return;
        }

        if (!$header = $this->getHeader('x-hub-signature')[0] ?? null) {
            throw new InvalidChannelRequest('Header signature is not provided');
        }

        if (!hash_equals($header, 'sha1='.hash_hmac('sha1', json_encode($this->getRequest()), $secret))) {
            throw new InvalidChannelRequest('Invalid signature header');
        }
    }

    /**
     * Return attachments from request
     *
     * @param string|null $type
     *
     * @return array|null
     */
    private function getRequestAttachments(string $type = null): ?array
    {
        $attachments = $this->getRequest('entry.0.messaging.0.message.attachments');

        if (is_null($type)) {
            return $attachments;
        }

        // Is it real to send many locations or something in one request?
        return collect($attachments)->first(function ($attachment) use ($type) {
            return $attachment['type'] === $type;
        });
    }
}
