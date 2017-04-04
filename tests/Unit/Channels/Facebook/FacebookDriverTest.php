<?php

declare(strict_types=1);

namespace Tests\Unit\Channels\Facebook;

use Tests\TestCase;
use GuzzleHttp\Client;
use FondBot\Helpers\Str;
use FondBot\Conversation\Keyboard;
use FondBot\Contracts\Channels\User;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;
use FondBot\Channels\Facebook\FacebookUser;
use FondBot\Channels\Facebook\FacebookDriver;
use FondBot\Conversation\Buttons\ReplyButton;
use FondBot\Contracts\Channels\Message\Location;
use FondBot\Contracts\Channels\Message\Attachment;
use FondBot\Channels\Facebook\FacebookOutgoingMessage;
use FondBot\Channels\Facebook\FacebookReceivedMessage;

/**
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface guzzle
 * @property array                                      $parameters
 * @property \FondBot\Channels\Facebook\FacebookDriver  facebook
 */
class FacebookDriverTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->guzzle = $this->mock(Client::class);
        $this->facebook = new FacebookDriver($this->guzzle);
        $this->facebook->fill($this->parameters = [
            'page_token' => Str::random(),
            'verify_token' => Str::random(),
            'app_secret' => Str::random(),
        ]);
    }

    public function test_getConfig()
    {
        $expected = [
            'page_token',
            'verify_token',
            'app_secret',
        ];

        $this->assertSame($expected, $this->facebook->getConfig());
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

        $this->facebook->fill([], $data, $this->generateHeaders($data, Str::random()));

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

        $this->facebook->fill($this->parameters, $data, $this->generateHeaders($data, Str::random()));

        $this->facebook->verifyRequest();
    }

    public function test_verifyRequest_valid_header()
    {
        $this->facebook->fill([], $data = $this->generateResponse(),
            $this->generateHeaders($data, $this->parameters['app_secret']));

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

        $this->facebook->fill($this->parameters, $data, $this->generateHeaders($data, $this->parameters['app_secret']));

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

        $this->facebook->fill($this->parameters, $data, $this->generateHeaders($data, $this->parameters['app_secret']));

        $this->facebook->verifyRequest();
    }

    public function test_verifyRequest()
    {
        $data = $this->generateResponse();

        $this->facebook->fill($this->parameters, $data, $this->generateHeaders($data, $this->parameters['app_secret']));

        $this->facebook->verifyRequest();
    }

    public function test_getSender()
    {
        $senderId = $this->faker()->uuid;
        $response = [
            'id' => $senderId,
            'first_name' => $this->faker()->firstName,
            'last_name' => $this->faker()->lastName,
            'profile_pic' => $this->faker()->url,
            'locale' => $this->faker()->locale,
            'timezone' => $this->faker()->randomDigit,
            'gender' => $this->faker()->word,
        ];

        $this->facebook->fill($this->parameters, $this->generateResponse($senderId));

        $stream = $this->mock(ResponseInterface::class);

        $stream->shouldReceive('getBody')->andReturn(json_encode($response))->atLeast()->once();
        $this->guzzle->shouldReceive('get')
            ->with('https://graph.facebook.com/v2.6/'.$senderId, [
                'query' => [
                    'access_token' => $this->parameters['page_token'],
                ],
            ])
            ->andReturn($stream)
            ->atLeast()->once();

        $sender = $this->facebook->getUser();

        $this->assertInstanceOf(User::class, $sender);
        $this->assertInstanceOf(FacebookUser::class, $sender);
        $this->assertSame($senderId, $sender->getId());
        $this->assertSame($response['first_name'].' '.$response['last_name'], $sender->getName());
        $this->assertNull($sender->getUsername());

        $this->assertSame($sender, $this->facebook->getUser());
    }

    /**
     * @expectedException \FondBot\Channels\Exceptions\InvalidChannelRequest
     * @expectedExceptionMessage Can not get user profile
     */
    public function test_getSender_exception()
    {
        $senderId = $this->faker()->uuid;

        $this->facebook->fill($this->parameters, $this->generateResponse($senderId));

        $this->guzzle->shouldReceive('get')
            ->with('https://graph.facebook.com/v2.6/'.$senderId, [
                'query' => [
                    'access_token' => $this->parameters['page_token'],
                ],
            ])
            ->andThrow(new RequestException('Invalid request', $this->mock(RequestInterface::class)));

        $result = $this->facebook->getUser();
        $this->assertInstanceOf(User::class, $result);
    }

    public function test_getMessage()
    {
        $this->facebook->fill($this->parameters, $this->generateResponse(null, $text = $this->faker()->text()));

        $message = $this->facebook->getMessage();
        $this->assertInstanceOf(FacebookReceivedMessage::class, $message);
        $this->assertFalse($message->hasAttachment());
        $this->assertSame($text, $message->getText());
        $this->assertNull($message->getLocation());
    }

    public function test_getMessageWithLocation()
    {
        $latitude = $this->faker()->latitude;
        $longitude = $this->faker()->longitude;

        $this->facebook->fill($this->parameters, $this->generateLocationResponse($latitude, $longitude));

        $message = $this->facebook->getMessage();
        $this->assertInstanceOf(FacebookReceivedMessage::class, $message);
        $this->assertFalse($message->hasAttachment());
        $this->assertInstanceOf(Location::class, $location = $message->getLocation());
        $this->assertSame($latitude, $location->getLatitude());
        $this->assertSame($longitude, $location->getLongitude());
        $this->assertNull($message->getText());
        $this->assertNull($message->getAttachment());
    }

    public function test_getMessageAttachments()
    {
        $this->mock(Client::class);

        $this->facebook->fill($this->parameters, $this->generateAttachmentResponse('audio'));
        $this->assertInstanceOf(Attachment::class, $this->facebook->getMessage()->getAttachment());
        $this->assertSame(Attachment::TYPE_AUDIO, $this->facebook->getMessage()->getAttachment()->getType());
        $this->assertTrue($this->facebook->getMessage()->hasAttachment());

        $this->facebook->fill($this->parameters, $this->generateAttachmentResponse('image'));
        $this->assertSame(Attachment::TYPE_IMAGE, $this->facebook->getMessage()->getAttachment()->getType());
        $this->assertTrue($this->facebook->getMessage()->hasAttachment());

        $this->facebook->fill($this->parameters, $this->generateAttachmentResponse('video'));
        $this->assertSame(Attachment::TYPE_VIDEO, $this->facebook->getMessage()->getAttachment()->getType());
        $this->assertTrue($this->facebook->getMessage()->hasAttachment());

        $this->facebook->fill($this->parameters, $this->generateAttachmentResponse('file'));
        $this->assertSame(Attachment::TYPE_FILE, $this->facebook->getMessage()->getAttachment()->getType());
        $this->assertTrue($this->facebook->getMessage()->hasAttachment());
    }

    public function test_sendMessage_with_keyboard()
    {
        $text = $this->faker()->text;

        $recipient = $this->mock(User::class);
        $recipient->shouldReceive('getId')->andReturn($recipientId = $this->faker()->uuid)->atLeast()->once();

        $keyboard = new Keyboard([
            new ReplyButton($this->faker()->word),
            new ReplyButton($this->faker()->word),
        ]);

        $this->guzzle->shouldReceive('post')->with(
            'https://graph.facebook.com/v2.6/me/messages',
            [
                'form_params' => [
                    'recipient' => [
                        'id' => $recipientId,
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
                    'access_token' => $this->parameters['page_token'],
                ],
            ]
        );

        $result = $this->facebook->sendMessage($recipient, $text, $keyboard);

        $this->assertInstanceOf(FacebookOutgoingMessage::class, $result);
        $this->assertSame($recipient, $result->getRecipient());
        $this->assertSame($text, $result->getText());
        $this->assertSame($keyboard, $result->getKeyboard());
    }

    public function test_verify_webhook_check()
    {
        $this->facebook->fill($this->parameters, [
            'hub_mode' => 'subscribe',
            'hub_verify_token' => $this->parameters['verify_token'],
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
        $this->facebook->fill($this->parameters, [
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

    private function generateLocationResponse(float $latitude, float $longitude): array
    {
        return [
            'entry' => [
                [
                    'messaging' => [
                        [
                            'sender' => [$this->faker()->uuid],
                            'message' => [
                                'attachments' => [
                                    [
                                        'title' => $this->faker()->sentence,
                                        'url' => $this->faker()->url,
                                        'type' => 'location',
                                        'payload' => [
                                            'coordinates' => [
                                                'lat' => $latitude ?: $this->faker()->latitude,
                                                'long' => $longitude ?: $this->faker()->longitude,
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    private function generateAttachmentResponse(string $type)
    {
        return [
            'entry' => [
                [
                    'messaging' => [
                        [
                            'sender' => [$this->faker()->uuid],
                            'message' => [
                                'attachments' => [
                                    [
                                        'type' => $type,
                                        'payload' => [
                                            'url' => $this->faker()->url,
                                        ],
                                    ],
                                ],
                            ],
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
