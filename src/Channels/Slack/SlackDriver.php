<?php

declare(strict_types = 1);

namespace FondBot\Channels\Slack;

use GuzzleHttp\Client;
use FondBot\Contracts\Conversation\Keyboard;
use FondBot\Contracts\Channels\Driver;
use FondBot\Contracts\Channels\Sender;
use FondBot\Contracts\Channels\Receiver;
use GuzzleHttp\Exception\RequestException;
use FondBot\Channels\Exceptions\InvalidChannelRequest;
use FondBot\Contracts\Channels\SenderMessage;
use FondBot\Contracts\Channels\ReceiverMessage;

class SlackDriver extends Driver
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
            'token'
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
            is_null( $this->getRequest('type') ) ||
            is_null( $this->getRequest('user') ) ||
            is_null( $this->getRequest('text') ) &&
            $this->getRequest('type') !== 'message'
        ) {
            throw new InvalidChannelRequest('Invalid payload');
        }
    }

    /**
     * Get message sender.
     *
     * @return Sender
     * @throws \Exception
     */
    public function getSender(): Sender
    {
        $from     = $this->getRequest('user');

        $userData = $this->guzzle->get($this->getBaseUrl() . $this->mapDriver('infoAboutUser'),
            [
                'query' => [
                    'token' => $this->getParameter('token'),
                    'user'  => $from
                ]
            ])->getBody();


        if ( ($responseUser = $this->jsonNormalize($userData))->ok === false)
        {
            throw new \Exception($responseUser->error);
        }

        return Sender::create(
            (string) $responseUser->user->id,
            $responseUser->user->profile->first_name .' '. $responseUser->user->profile->last_name,
            $responseUser->user->name
        );
    }

    /**
     * Get message received from sender.
     *
     * @return SenderMessage
     */
    public function getMessage(): SenderMessage
    {
        return new SlackSenderMessage($this->getRequest());
    }

    /**
     *  Send reply to participant.
     *
     * @param Receiver      $receiver
     * @param string        $text
     * @param Keyboard|null $keyboard
     * @return ReceiverMessage
     */
    public function sendMessage(Receiver $receiver, string $text, Keyboard $keyboard = null): ReceiverMessage
    {
        $message = new SlackReceiverMessage($receiver, $text, $keyboard);

        $query   = array_merge($message->toArray(), [
            'token'   => $this->getParameter('token')
        ]);

        try {
            $this->guzzle->post($this->getBaseUrl() . $this->mapDriver('postMessage'), [
                'query' => $query
            ]);
        } catch (RequestException $exception) {
            $this->error(get_class($exception), [$exception->getMessage()]);
        }

        return $message;
    }

    private function getBaseUrl(): string
    {
        return  'https://slack.com/api/';
    }

    /**
     * Getting json conversion from guzzle
     *
     * @param $guzzleBody
     * @return mixed
     */
    private function jsonNormalize($guzzleBody)
    {
        return json_decode((string) $guzzleBody);
    }

    /**
     * The array method for correct job slack driver
     *
     * @param string $name
     * @return string
     * @throws \Exception
     */
    private function mapDriver(string $name) : string
    {
        $map =  [
            'infoAboutUser' => 'users.info',
            'postMessage'   => 'chat.postMessage'
        ];

        if ( isset($map[$name]) )
        {
            return $map[$name];
        } else{
            throw new \Exception('no matches');
        }

    }
}
