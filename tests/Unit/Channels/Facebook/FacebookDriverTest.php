<?php

declare(strict_types=1);

namespace Tests\Unit\Channels\Facebook;

use FondBot\Channels\Facebook\FacebookReceiverMessage;
use FondBot\Conversation\Keyboards\BasicKeyboard;
use FondBot\Conversation\Keyboards\Button;
use Tests\TestCase;
use GuzzleHttp\Client;
use FondBot\Contracts\Channels\Sender;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use FondBot\Contracts\Channels\Receiver;
use GuzzleHttp\Exception\RequestException;
use FondBot\Channels\Facebook\FacebookDriver;
use FondBot\Channels\Facebook\FacebookSenderMessage;
use FondBot\Contracts\Database\Entities\Channel;

/**
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface guzzle
 * @property Channel channel
 * @property FacebookDriver facebook
 */
class FacebookDriverTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->guzzle = $this->mock(Client::class);
        $this->channel = new Channel([
            'driver' => FacebookDriver::class,
            'name' => $this->faker()->name,
            'parameters' => [
                'page_token' => str_random(),
                'verify_token' => str_random(),
                'app_secret' => str_random(),
            ],
        ]);

        $this->facebook = new FacebookDriver($this->guzzle);
        $this->facebook->setChannel($this->channel);
        $this->facebook->setRequest([]);
        $this->facebook->setHeaders([]);
    }

    public function test_getConfig()
    {
        $expected = [
            'page_token',
            'verify_token',
            'app_secret',
        ];

        $this->assertEquals($expected, $this->facebook->getConfig());
    }

    /**
     * @expectedException \FondBot\Channels\Exceptions\InvalidChannelRequest
     * @expectedExceptionMessage Header signature is not provided
     */
    public function test_verifyRequest_invalid_header()
    {
        $this->facebook->verifyRequest();
    }

    public function test_verifyRequest_skip_signature()
    {
        $data = $this->generateResponse();
        $this->shouldReturnAttribute($channel = $this->mock(Channel::class), 'parameters', []);

        $this->facebook->setHeaders($this->generateHeaders($data, str_random()));
        $this->facebook->setRequest($data);
        $this->facebook->setChannel($channel);

        $this->facebook->verifyRequest();
    }

    /**
     * @expectedException \FondBot\Channels\Exceptions\InvalidChannelRequest
     * @expectedExceptionMessage Invalid signature header
     */
    public function test_verifyRequest_invalid_secret()
    {
        $data = [
            'foo' => 'bar',
        ];

        $this->facebook->setRequest($data);
        $this->facebook->setHeaders($this->generateHeaders($data, str_random()));

        $this->facebook->verifyRequest();
    }

    public function test_verifyRequest_valid_header()
    {
        $this->facebook->setRequest($data = $this->generateResponse());
        $this->facebook->setHeaders($this->generateHeaders($data, $this->channel->parameters['app_secret']));

        $this->facebook->verifyRequest();
    }

    /**
     * @expectedException \FondBot\Channels\Exceptions\InvalidChannelRequest
     * @expectedExceptionMessage Invalid payload
     */
    public function test_verifyRequest_empty_message()
    {
        $data = [
            'foo' => 'bar',
        ];

        $this->facebook->setRequest($data);
        $this->facebook->setHeaders($this->generateHeaders($data, $this->channel->parameters['app_secret']));

        $this->facebook->verifyRequest();
    }

    /**
     * @expectedException \FondBot\Channels\Exceptions\InvalidChannelRequest
     * @expectedExceptionMessage Invalid payload
     */
    public function test_verifyRequest_empty_message_from()
    {
        $data = [
            'entry' => [
                [
                    'messaging' => [
                        [
                            'message' => [
                                'text' => $this->faker()->word,
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $this->facebook->setHeaders($this->generateHeaders($data, $this->channel->parameters['app_secret']));
        $this->facebook->setRequest($data);

        $this->facebook->verifyRequest();
    }

    public function test_verifyRequest()
    {
        $data = $this->generateResponse();

        $this->facebook->setHeaders($this->generateHeaders($data, $this->channel->parameters['app_secret']));
        $this->facebook->setRequest($data);

        $this->facebook->verifyRequest();
    }

    public function test_getSender()
    {
        $senderId = $this->faker()->uuid;
        $response = [
            'first_name' => $this->faker()->firstName,
            'last_name' => $this->faker()->lastName,
            'profile_pic' => $this->faker()->url,
            'locale' => $this->faker()->locale,
            'timezone' => $this->faker()->randomDigit,
            'gender' => $this->faker()->word,
        ];

        $this->facebook->setRequest($this->generateResponse($senderId));

        $stream = $this->mock(ResponseInterface::class);

        $stream->shouldReceive('getBody')->andReturn(json_encode($response));
        $this->guzzle->shouldReceive('get')->with('https://graph.facebook.com/v2.6/'.$senderId, [
            'query' => [
                'access_token' => $this->channel->parameters['page_token'],
            ],
        ])->andReturn($stream);

        $this->assertInstanceOf(Sender::class, $this->facebook->getSender());
    }

    /**
     * @expectedException \FondBot\Channels\Exceptions\InvalidChannelRequest
     * @expectedExceptionMessage Can not get user profile
     */
    public function test_getSender_exception()
    {
        $senderId = $this->faker()->uuid;
        $this->facebook->setRequest($this->generateResponse($senderId));

        $this->guzzle->shouldReceive('get')->with('https://graph.facebook.com/v2.6/'.$senderId, [
            'query' => [
                'access_token' => $this->channel->parameters['page_token'],
            ],
        ])->andThrow(new RequestException('Invalid request', $this->mock(RequestInterface::class)));

        $this->facebook->getSender();
    }

    public function test_getMessage()
    {
        $this->facebook->setRequest($this->generateResponse(null, $text = $this->faker()->text()));

        $message = $this->facebook->getMessage();
        $this->assertInstanceOf(FacebookSenderMessage::class, $message);
        $this->assertSame($text, $message->getText());
    }

    public function test_sendMessage_with_keyboard()
    {
        $text = $this->faker()->text;

        $receiver = new Receiver($this->faker()->uuid);
        $keyboard = new BasicKeyboard([
            new Button($this->faker()->word),
            new Button($this->faker()->word),
        ]);

        $this->guzzle->shouldReceive('post')->with(
            'https://graph.facebook.com/v2.6/me/messages',
            [
                'form_params' => [
                    'recipient' => [
                        'id' => $receiver->getIdentifier(),
                    ],
                    'message' => [
                        'text' => $text,
                        'quick_replies' => [
                            [
                                'content_type' => 'text',
                                'title' => $keyboard->getButtons()[0]->getLabel(),
                                'payload' => $keyboard->getButtons()[0]->getLabel(),
                            ],
                            [
                                'content_type' => 'text',
                                'title' => $keyboard->getButtons()[1]->getLabel(),
                                'payload' => $keyboard->getButtons()[1]->getLabel(),
                            ],
                        ],
                    ],
                ],
                'query' => [
                    'access_token' => $this->channel->parameters['page_token'],
                ],
            ]
        );

        $result = $this->facebook->sendMessage($receiver, $text, $keyboard);

        $this->assertInstanceOf(FacebookReceiverMessage::class, $result);
        $this->assertSame($receiver, $result->getReceiver());
        $this->assertSame($text, $result->getText());
        $this->assertSame($keyboard, $result->getKeyboard());
    }

    public function test_sendMessage_request_exception()
    {
        $text = $this->faker()->text;
        $receiver = $this->mock(Receiver::class);
        $receiver->shouldReceive('getIdentifier')->andReturn($chatId = $this->faker()->uuid);

        $this->guzzle->shouldReceive('post')->andThrow(new RequestException('Invalid request',
            $this->mock(RequestInterface::class)));

        $this->facebook->sendMessage($receiver, $text);
    }

    public function test_verify_webhook_check()
    {
        $this->facebook->setRequest([
            'hub_mode' => 'subscribe',
            'hub_verify_token' => $this->channel->parameters['verify_token'],
            'hub_challenge' => $challenge = $this->faker()->randomNumber(),
        ]);

        $this->assertTrue($this->facebook->isVerificationRequest());
        $this->assertEquals($challenge, $this->facebook->verifyWebhook());
    }

    /**
     * @expectedException \FondBot\Channels\Exceptions\InvalidChannelRequest
     * @expectedExceptionMessage Invalid verify token
     */
    public function test_verifyWebhook_invalid_token()
    {
        $this->facebook->setRequest([
            'hub_mode' => 'subscribe',
            'hub_verify_token' => $this->faker()->word,
            'hub_challenge' => $challenge = $this->faker()->randomNumber(),
        ]);

        $this->assertTrue($this->facebook->isVerificationRequest());
        $this->facebook->verifyWebhook();
    }

    private function generateSignature(array $data, $key): string
    {
        return 'sha1='.hash_hmac('sha1', json_encode($data), $key);
    }

    private function generateResponse(string $id = null, string $text = null): array
    {
        return [
            'entry' => [
                [
                    'messaging' => [
                        [
                            'sender' => ['id' => $id ?: $this->faker()->uuid],
                            'message' => ['text' => $text ?: $this->faker()->word],
                        ],
                    ],
                ],
            ],
        ];
    }

    private function generateHeaders(array $data, $key): array
    {
        return [
            'x-hub-signature' => [$this->generateSignature($data, $key)],
        ];
    }
}
