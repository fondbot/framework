<?php

declare(strict_types=1);

namespace Unit\Channels\Facebook;

use Tests\TestCase;
use GuzzleHttp\Client;
use FondBot\Channels\Sender;
use FondBot\Channels\Message;
use FondBot\Channels\Receiver;
use FondBot\Conversation\Keyboard;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use FondBot\Conversation\Keyboards\Button;
use GuzzleHttp\Exception\RequestException;
use Fondbot\Channels\Facebook\FacebookDriver;
use FondBot\Contracts\Database\Entities\Channel;

/**
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface guzzle
 * @property Channel                                    channel
 * @property FacebookDriver                             facebook
 */
class FacebookDriverTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->guzzle = $this->mock(Client::class);
        $this->channel = new Channel([
            'driver'     => FacebookDriver::class,
            'name'       => $this->faker()->name,
            'parameters' => [
                'page_token'   => str_random(),
                'verify_token' => $this->faker()->word,
                'app_secret'   => str_random(),
            ],
        ]);

        $this->facebook = new FacebookDriver($this->guzzle);
        $this->facebook->setChannel($this->channel);
        $this->facebook->setRequest([]);
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
     * @expectedExceptionMessage Invalid payload
     */
    public function test_verifyRequest_empty_message()
    {
        $this->facebook->verifyRequest();
    }

    /**
     * @expectedException \FondBot\Channels\Exceptions\InvalidChannelRequest
     * @expectedExceptionMessage Invalid payload
     */
    public function test_verifyRequest_empty_message_from()
    {
        $this->facebook->setRequest([
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
        ]);

        $this->facebook->verifyRequest();
    }

    public function test_verifyRequest()
    {
        $this->facebook->setRequest([
            'entry' => [
                [
                    'messaging' => [
                        [
                            'sender'  => [
                                'id' => $this->faker()->uuid,
                            ],
                            'message' => [
                                'text' => $this->faker()->word,
                            ],
                        ],
                    ],
                ],
            ],
        ]);

        $this->facebook->verifyRequest();
    }

    public function test_getSender()
    {
        $senderId = $this->faker()->uuid;
        $response = [
            'first_name'  => $this->faker()->firstName,
            'last_name'   => $this->faker()->lastName,
            'profile_pic' => $this->faker()->url,
            'locale'      => $this->faker()->locale,
            'timezone'    => $this->faker()->randomDigit,
            'gender'      => $this->faker()->word,
        ];
        $this->facebook->setRequest([
            'entry' => [
                [
                    'messaging' => [
                        [
                            'sender' => [
                                'id' => $senderId,
                            ],
                        ],
                    ],
                ],
            ],
        ]);

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
        $this->facebook->setRequest([
            'entry' => [
                [
                    'messaging' => [
                        [
                            'sender' => [
                                'id' => $senderId,
                            ],
                        ],
                    ],
                ],
            ],
        ]);

        $this->guzzle->shouldReceive('get')->with('https://graph.facebook.com/v2.6/'.$senderId, [
            'query' => [
                'access_token' => $this->channel->parameters['page_token'],
            ],
        ])->andThrow(new RequestException('Invalid request', $this->mock(RequestInterface::class)));

        $this->facebook->getSender();
    }

    public function test_getMessage()
    {
        $this->facebook->setRequest([
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
        ]);

        $this->assertInstanceOf(Message::class, $this->facebook->getMessage());
    }

    public function test_sendMessage_with_keyboard()
    {
        $text = $this->faker()->text;

        $receiver = $this->mock(Receiver::class);
        $keyboard = $this->mock(Keyboard::class);
        $button1 = $this->mock(Button::class);
        $button2 = $this->mock(Button::class);

        $receiver->shouldReceive('getIdentifier')->andReturn($chatId = $this->faker()->uuid);
        $keyboard->shouldReceive('getButtons')->andReturn([$button1, $button2]);
        $button1->shouldReceive('getValue')->andReturn($button1Text = $this->faker()->word);
        $button2->shouldReceive('getValue')->andReturn($button2Text = $this->faker()->word);

        $this->guzzle->shouldReceive('post')->with(
            'https://graph.facebook.com/v2.6/me/messages',
            [
                'form_params' => [
                    'recipient' => [
                        'id' => $chatId,
                    ],
                    'message'   => [
                        'text'          => $text,
                        'quick_replies' => [
                            [
                                'content_type' => 'text',
                                'title'        => $button1Text,
                                'payload'      => $button1Text,
                            ],
                            [
                                'content_type' => 'text',
                                'title'        => $button2Text,
                                'payload'      => $button2Text,
                            ],
                        ],
                    ],
                ],
                'query'       => [
                    'access_token' => $this->channel->parameters['page_token'],
                ],
            ]
        );

        $this->facebook->sendMessage($receiver, $text, $keyboard);
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

    public function test_verification_request_check_webhook()
    {
        $this->facebook->setRequest([
            'hub_mode'         => 'subscribe',
            'hub_verify_token' => $this->faker()->word,
        ]);

        $this->assertTrue($this->facebook->isVerificationRequestFailed());
    }

    public function test_verification_request_app_secret()
    {
        $this->assertTrue($this->facebook->isVerificationRequestFailed());
    }

    public function test_verification_request_not_set_app_secret()
    {
        $channel = $this->mock(Channel::class);

        $channel->shouldReceive('getAttribute')->andReturn(['app_secret' => '']);

        $this->facebook->setChannel($channel);

        $this->assertFalse($this->facebook->isVerificationRequestFailed());
    }

    public function test_verify_webhook_check()
    {
        $this->facebook->setRequest([
            'hub_mode'         => 'subscribe',
            'hub_verify_token' => $this->channel->parameters['verify_token'],
            'hub_challenge'    => $challenge = $this->faker()->randomNumber(),
        ]);

        $this->assertEquals($challenge, $this->facebook->verifyWebhook());
    }

    public function test_verify_webhook_ok()
    {
        $channel = $this->mock(Channel::class);

        $channel->shouldReceive('getAttribute')->andReturn(['app_secret' => '']);

        $this->facebook->setChannel($channel);

        $this->assertEquals('OK', $this->facebook->verifyWebhook());
    }
}
