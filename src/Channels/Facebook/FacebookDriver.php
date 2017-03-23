<?php

declare(strict_types=1);

namespace FondBot\Channels\Facebook;

use GuzzleHttp\Client;
use FondBot\Contracts\Channels\Driver;
use FondBot\Contracts\Channels\Sender;
use FondBot\Contracts\Channels\Receiver;
use GuzzleHttp\Exception\RequestException;
use FondBot\Contracts\Conversation\Keyboard;
use FondBot\Contracts\Channels\SenderMessage;
use FondBot\Contracts\Channels\ReceiverMessage;
use FondBot\Contracts\Channels\Extensions\WebhookVerification;
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

        if (!$this->hasRequest('entry.0.messaging.0.sender.id') || !$this->hasRequest('entry.0.messaging.0.message')) {
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
     * @return SenderMessage
     */
    public function getMessage(): SenderMessage
    {
        return new FacebookSenderMessage($this->getRequest('entry.0.messaging.0.message'));
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
        $message = new FacebookReceiverMessage($receiver, $text, $keyboard);

        try {
            $this->guzzle->post(
                $this->getBaseUrl().'me/messages',
                $this->getDefaultRequestParameters() + ['form_params' => $message->toArray()]
            );
        } catch (RequestException $exception) {
            $this->error(get_class($exception), [$exception->getMessage()]);
        }

        return $message;
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
}
