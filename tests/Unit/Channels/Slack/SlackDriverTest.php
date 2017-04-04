<?php

declare(strict_types = 1);

namespace Tests\Unit\Channels\Slack;

use FondBot\Channels\Slack\SlackDriver;
use FondBot\Channels\Slack\SlackReceivedMessage;
use FondBot\Channels\Slack\SlackUser;
use FondBot\Contracts\Channels\OutgoingMessage;
use FondBot\Contracts\Channels\User;
use FondBot\Helpers\Str;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\RequestInterface;
use Tests\TestCase;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

/**
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface guzzle
 * @property Channel channel
 * @property SlackDriverTest telegram
 */
class SlackDriverTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->guzzle = $this->mock(Client::class);
        $this->slack  = new SlackDriver($this->guzzle);
        $this->slack->fill($this->parameters = ['token' => Str::random()]);

    }

    public function test_getConfig()
    {
        $expected = ['token'];

        $this->assertEquals($expected, $this->slack->getConfig());
    }

    public function test_getHeaders()
    {
        $this->slack->fill($this->parameters, [], $headers = ['Token' => $this->faker()->uuid]);

        $this->assertSame($headers['Token'], $this->slack->getHeader('Token'));
        $this->assertSame($headers, $this->slack->getHeaders());
    }

    /**
     * @expectedException \FondBot\Channels\Exceptions\InvalidChannelRequest
     * @expectedExceptionMessage Invalid payload
     */
    public function test_verifyRequest_empty_message()
    {
        $this->slack->verifyRequest();
    }

    /**
     * @expectedException \FondBot\Channels\Exceptions\InvalidChannelRequest
     * @expectedExceptionMessage Invalid payload
     */
    public function test_verifyRequest_no_sender()
    {
        $this->slack->fill($this->parameters, ['message' => []]);
        $this->slack->verifyRequest();
    }

    public function test_verifyRequest()
    {
        $request = ['type' => 'message', 'user' => $this->faker()->name, 'text' => $this->faker()->word];
        $this->slack->fill($this->parameters, $request);
        $this->slack->verifyRequest();
    }

    public function test_getSender()
    {
        $senderId =  Str::random();

        $response = [
            'ok'         => true,
            'user' => [
                'id'         =>  $senderId,
                'name'       => $this->faker()->userName,
                'profile'    => [
                    'first_name' => $this->faker()->firstName,
                    'last_name'  => $this->faker()->lastName,
                ]
            ]
        ];
        $preData = [
            "type" => "message",
            "text" => "Hello world",
            "user" => $senderId,
        ];
        $this->slack->fill($this->parameters, $preData);
        $stream = $this->mock(ResponseInterface::class);

        $stream->shouldReceive('getBody')->andReturn(json_encode($response))->atLeast()->once();
        $this->guzzle->shouldReceive('get')
            ->with('https://slack.com/api/users.info', [
                'query' => [
                    'token' => $this->parameters['token'],
                    'user'  => $senderId
                ],
            ])
            ->andReturn($stream)
            ->atLeast()->once();

        $sender = $this->slack->getUser();
        $this->assertInstanceOf(User::class, $sender);
        $this->assertInstanceOf(SlackUser::class, $sender);
        $this->assertSame($senderId, $sender->getId());
        $this->assertSame($response['user']['profile']['first_name'].' '.$response['user']['profile']['last_name'], $sender->getName());
        $this->assertSame($response['user']['name'], $sender->getUsername());


    }

    public function test_getMessage()
    {
        $this->slack->fill($this->parameters, [
                'text' => $text = $this->faker()->text,
        ]);
        $message = $this->slack->getMessage();
        $this->assertInstanceOf(SlackReceivedMessage::class, $message);
        $this->assertSame($text, $message->getText());
        $this->assertNull($message->getAttachment());
        $this->assertNull($message->getLocation());

    }

    public function test_sendMessage_request_exception()
    {
        $senderId =  Str::random();
        $text     =  $this->faker()->text;

        $response = [
            'ok'         => true,
            'user' => [
                'id'         =>  $senderId,
                'name'       => $this->faker()->userName,
                'profile'    => [
                    'first_name' => $this->faker()->firstName,
                    'last_name'  => $this->faker()->lastName,
                ]
            ]
        ];

        $preData = [
            "type" => "message",
            "text" => "Hello world",
            "user" => $senderId,
        ];
        $this->slack->fill($this->parameters, $preData);
        $stream = $this->mock(ResponseInterface::class);

        $stream->shouldReceive('getBody')->andReturn(json_encode($response))->atLeast()->once();
        $this->guzzle->shouldReceive('get')
            ->with('https://slack.com/api/users.info', [
                'query' => [
                    'token' => $this->parameters['token'],
                    'user'  => $senderId
                ],
            ])
            ->andReturn($stream)
            ->atLeast()->once();
        $sender = $this->slack->getUser();
        $this->guzzle->shouldReceive('post')->andThrow(new RequestException('Invalid request',
            $this->mock(RequestInterface::class)));

        $this->slack->sendMessage($sender, $text);

    }

    public function test_sendMessage()
    {
        $senderId =  Str::random();
        $text     =  $this->faker()->text;
        $response = [
            'ok'         => true,
            'user' => [
                'id'         =>  $senderId,
                'name'       => $this->faker()->userName,
                'profile'    => [
                    'first_name' => $this->faker()->firstName,
                    'last_name'  => $this->faker()->lastName,
                ]
            ]
        ];
        $preData  = [
            "type" => "message",
            "text" => $text,
            "user" => $senderId,
        ];

        $this->slack->fill($this->parameters, $preData);

        $stream = $this->mock(ResponseInterface::class);

        $stream->shouldReceive('getBody')->andReturn(json_encode($response))->atLeast()->once();

        $this->guzzle->shouldReceive('get')
            ->with('https://slack.com/api/users.info', [
                'query' => [
                    'token' => $this->parameters['token'],
                    'user'  => $senderId
                ],
            ])
            ->andReturn($stream)
            ->atLeast()->once();

        $sender = $this->slack->getUser();

        $this->guzzle->shouldReceive('post')->with('https://slack.com/api/chat.postMessage', [
            'form_params' => [
                'token'   => $this->parameters['token'],
                'channel' => $senderId,
                'text'    => $text,
            ]
        ]);

        $senderObject = $this->slack->sendMessage($sender, $text);
        $this->assertInstanceOf(OutgoingMessage::class, $senderObject);
    }
}
