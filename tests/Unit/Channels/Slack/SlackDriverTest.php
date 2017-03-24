<?php

declare(strict_types = 1);

namespace Tests\Unit\Channels\Slack;

use FondBot\Channels\Slack\SlackDriver;
use FondBot\Channels\Slack\SlackMessage;
use FondBot\Contracts\Channels\Receiver;
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
        $this->channel = new Channel([
            'driver'     => SlackDriver::class,
            'name'       => $this->faker()->name,
            'parameters' => ['token' => str_random()],
        ]);

        $this->slack = new SlackDriver($this->guzzle);
        $this->slack->setChannel($this->channel);
        $this->slack->setRequest([]);
    }
    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }

    public function test_getChannel()
    {
        $this->assertSame($this->channel, $this->slack->getChannel());
    }

    public function test_getConfig()
    {
        $expected = ['token'];

        $this->assertEquals($expected, $this->slack->getConfig());
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
        $this->slack->setRequest(['text' => []]);

        $this->slack->verifyRequest();
    }

    public function test_verifyRequest()
    {
        $this->slack->setRequest(['type' => 'message', 'user' => $this->faker()->name, 'text' => $this->faker()->word]);
        $this->slack->verifyRequest();
    }

    public function test_installWebhook()
    {
//        $url = $this->faker()->url;
//
//        $this->guzzle->shouldReceive('post')->with(
//            'https://api.telegram.org/bot'.$this->channel->parameters['token'].'/setWebhook',
//            [
//                'form_params' => [
//                    'url' => $url,
//                ],
//            ]
//        )->once();
//
//        $this->telegram->installWebhook($url);
    }

    public function test_getSender()
    {
        $senderId = $this->faker()->uuid;
        $this->slack->setRequest(['user' => $senderId ]);

        $response =   [
            'ok'         => true,
            'user' => [
                'id'         => str_random(),
                'name'       => $this->faker()->userName,
                'profile' => [
                    'first_name' => $this->faker()->firstName,
                    'last_name'  => $this->faker()->lastName,
                ]
            ]
        ];
        $stream   = $this->mock(ResponseInterface::class);

        $stream->shouldReceive('getBody')->andReturn(json_encode((object)$response));

        $this->guzzle->shouldReceive('get')->with('https://slack.com/api/users.info', [
            'query' => [
                'token' => $this->slack->getParameter('token'),
                'user'  => $senderId
            ]
        ])->andReturn($stream);

        $this->assertInstanceOf(Sender::class, $this->slack->getSender());

    }

    public function test_getMessage()
    {
        $this->slack->setRequest([
                'text' => $text = $this->faker()->text,
        ]);

        /** @var TelegramMessage $message */
        $message = $this->slack->getMessage();
        $this->assertInstanceOf(SlackMessage::class, $message);
        $this->assertSame($text, $message->getText());
    }

    public function test_sendMessage_request_exception()
    {
        $text     = $this->faker()->text;
        $receiver = $this->mock(Receiver::class);

        $receiver->shouldReceive('getIdentifier')->andReturn( $this->faker()->uuid );

        $this->guzzle->shouldReceive('post')->andThrow(new RequestException('Invalid request',
            $this->mock(RequestInterface::class)));

        $this->slack->sendMessage($receiver, $text);
    }

    public function test_sendMessage()
    {
        $receiver = Receiver::create($this->faker()->uuid, $this->faker()->name);
        $text     = $this->faker()->text();

        $this->guzzle->shouldReceive('post')
            ->with(
                'https://slack.com/api/chat.postMessage',
                [
                    'query' => [
                        'channel' => $receiver->getIdentifier(),
                        'text'    => $text,
                        'token'   => $this->slack->getParameter('token')
                    ]
                ]
            )
            ->once();

        $this->slack->sendMessage($receiver, $text);
    }
}
