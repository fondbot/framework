<?php

declare(strict_types = 1);

namespace Tests\Unit\Channels\Slack;

use FondBot\Channels\Slack\SlackDriver;

use FondBot\Channels\Slack\SlackReceivedMessage;
use FondBot\Channels\Slack\SlackUser;
use FondBot\Contracts\Channels\OutgoingMessage;
use FondBot\Contracts\Channels\Receiver;
use FondBot\Contracts\Channels\ReceiverMessage;
use FondBot\Contracts\Channels\User;
use FondBot\Helpers\Str;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\RequestInterface;
use Tests\TestCase;
use GuzzleHttp\Client;
use FondBot\Contracts\Channels\Sender;
use Psr\Http\Message\ResponseInterface;
use FondBot\Contracts\Database\Entities\Channel;
use Mockery as m;

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
        $this->slack->fill($this->parameters, $response = [
            'ok'         => true,
            'user' => [
                   'id'          =>  Str::random(),
                    'name'       => $this->faker()->userName,
                    'profile'    => [
                        'first_name' => $this->faker()->firstName,
                        'last_name'  => $this->faker()->lastName,
                ]
            ]
        ]);
        $sender = $this->slack->getUser();
        $this->assertInstanceOf(User::class, $sender);
        $this->assertInstanceOf(SlackUser::class, $sender);
        $this->assertSame($response['user']['id'], $sender->getId());
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
        $this->slack->fill([], $response = [
            'ok'         => true,
            'user' => [
                'id'          =>  Str::random(),
                'name'       => $this->faker()->userName,
                'profile'    => [
                    'first_name' => $this->faker()->firstName,
                    'last_name'  => $this->faker()->lastName,
                ]
            ]
        ]);

        $text     = $this->faker()->text;
        $sender   = $this->slack->getUser();

        $this->guzzle->shouldReceive('post')->andThrow(new RequestException('Invalid request',
            $this->mock(RequestInterface::class)));

        $this->slack->sendMessage($sender, $text);
    }

    public function test_sendMessage()
    {
        $this->slack->fill($this->parameters, $response = [
            'user' => [
                'id'          =>  Str::random(),
                'name'       => $this->faker()->userName,
                'profile'    => [
                    'first_name' => $this->faker()->firstName,
                    'last_name'  => $this->faker()->lastName,
                ]
            ]
        ],  $headers = ['token' => $this->faker()->uuid]);
//        $this->slack->fill($this->parameters, [], $headers = ['token' => $this->faker()->uuid]);
//var_dump($this->slack->getHeader('token'));die();
        $sender = $this->slack->getUser();
        $text   = $this->faker()->text();
        $this->guzzle->shouldReceive('post')
            ->with(
                'https://slack.com/api/chat.postMessage',
                [
                    'form_params' => [
                        'channel' => $sender->getId(),
                        'text'    => $this->faker()->text(),
                        'token'   => $this->slack->getHeader('token')
                    ]
                ]
            )
            ->once();

        $senderObject = $this->slack->sendMessage($sender, $text);
        $this->assertInstanceOf(OutgoingMessage::class, $senderObject);
    }
}
