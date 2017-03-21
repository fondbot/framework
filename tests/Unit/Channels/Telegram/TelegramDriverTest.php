<?php

declare(strict_types=1);

namespace Tests\Unit\Channels\Drivers;

use FondBot\Contracts\Channels\Message\Location;
use Tests\TestCase;
use GuzzleHttp\Client;
use FondBot\Conversation\Keyboard;
use FondBot\Contracts\Channels\Sender;
use Psr\Http\Message\RequestInterface;
use FondBot\Contracts\Channels\Message;
use FondBot\Contracts\Channels\Receiver;
use FondBot\Conversation\Keyboards\Button;
use GuzzleHttp\Exception\RequestException;
use FondBot\Channels\Telegram\TelegramDriver;
use FondBot\Contracts\Database\Entities\Channel;

/**
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface guzzle
 * @property Channel channel
 * @property TelegramDriver telegram
 */
class TelegramDriverTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->guzzle = $this->mock(Client::class);
        $this->channel = new Channel([
            'driver' => TelegramDriver::class,
            'name' => $this->faker()->name,
            'parameters' => ['token' => str_random()],
        ]);

        $this->telegram = new TelegramDriver($this->guzzle);
        $this->telegram->setChannel($this->channel);
        $this->telegram->setRequest([]);
    }

    public function test_getChannel()
    {
        $this->assertSame($this->channel, $this->telegram->getChannel());
    }

    public function test_getConfig()
    {
        $expected = ['token'];

        $this->assertEquals($expected, $this->telegram->getConfig());
    }

    public function test_getHeaders()
    {
        $this->telegram->setHeaders($headers = ['Token' => $this->faker()->uuid]);

        $this->assertSame($headers['Token'], $this->telegram->getHeader('Token'));
        $this->assertSame($headers, $this->telegram->getHeaders());
    }

    /**
     * @expectedException \FondBot\Channels\Exceptions\InvalidChannelRequest
     * @expectedExceptionMessage Invalid payload
     */
    public function test_verifyRequest_empty_message()
    {
        $this->telegram->verifyRequest();
    }

    /**
     * @expectedException \FondBot\Channels\Exceptions\InvalidChannelRequest
     * @expectedExceptionMessage Invalid payload
     */
    public function test_verifyRequest_empty_message_from()
    {
        $this->telegram->setRequest(['message' => ['text' => $this->faker()->word]]);

        $this->telegram->verifyRequest();
    }

    public function test_verifyRequest()
    {
        $this->telegram->setRequest(['message' => ['from' => $this->faker()->name, 'text' => $this->faker()->word]]);

        $this->telegram->verifyRequest();
    }

    /**
     * @expectedException \FondBot\Channels\Exceptions\InvalidChannelRequest
     * @expectedExceptionMessage Invalid payload
     */
    public function test_verifyRequest_empty_message_text()
    {
        request()->setJson(collect(['message' => ['from' => $this->faker()->name]]));

        $this->telegram->verifyRequest();
    }

    public function test_installWebhook()
    {
        $url = $this->faker()->url;

        $this->guzzle->shouldReceive('post')->with(
            'https://api.telegram.org/bot'.$this->channel->parameters['token'].'/setWebhook',
            [
                'form_params' => [
                    'url' => $url,
                ],
            ]
        );

        $this->telegram->installWebhook($url);
    }

    public function test_getSender()
    {
        $this->telegram->setRequest([
            'message' => [
                'from' => [
                    'id' => str_random(),
                    'first_name' => $this->faker()->firstName,
                    'last_name' => $this->faker()->lastName,
                    'username' => $this->faker()->userName,
                ],
            ],
        ]);

        $this->assertInstanceOf(Sender::class, $this->telegram->getSender());
    }

    public function test_getMessage()
    {
        $this->telegram->setRequest([
            'message' => [
                'text' => $this->faker()->text,
            ],
        ]);

        $this->assertInstanceOf(Message::class, $this->telegram->getMessage());
    }

    public function test_getMessage_with_location()
    {
        $latitude = $this->faker()->latitude;
        $longitude = $this->faker()->longitude;

        $this->telegram->setRequest([
            'message' => [
                'text' => $this->faker()->text,
                'location' => [
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                ],
            ],
        ]);

        $message = $this->telegram->getMessage();
        $this->assertInstanceOf(Message::class, $message);

        $location = $message->getLocation();
        $this->assertInstanceOf(Location::class, $location);
        $this->assertSame($latitude, $location->getLatitude());
        $this->assertSame($longitude, $location->getLongitude());
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

        $replyMarkup = json_encode([
            'keyboard' => [
                [
                    (object) ['text' => $button1Text],
                    (object) ['text' => $button2Text],
                ],
            ],
            'resize_keyboard' => true,
        ]);

        $this->guzzle->shouldReceive('post')->with(
            'https://api.telegram.org/bot'.$this->channel->parameters['token'].'/sendMessage',
            [
                'form_params' => [
                    'chat_id' => $chatId,
                    'text' => $text,
                    'reply_markup' => $replyMarkup,
                ],
            ]
        );

        $this->telegram->sendMessage($receiver, $text, $keyboard);
    }

    public function test_sendMessage_without_keyboard()
    {
        $text = $this->faker()->text;

        $receiver = $this->mock(Receiver::class);
        $receiver->shouldReceive('getIdentifier')->andReturn($chatId = $this->faker()->uuid);

        $this->guzzle->shouldReceive('post')->with(
            'https://api.telegram.org/bot'.$this->channel->parameters['token'].'/sendMessage',
            [
                'form_params' => [
                    'chat_id' => $chatId,
                    'text' => $text,
                ],
            ]
        );

        $this->telegram->sendMessage($receiver, $text);
    }

    public function test_sendMessage_request_exception()
    {
        $text = $this->faker()->text;
        $receiver = $this->mock(Receiver::class);
        $receiver->shouldReceive('getIdentifier')->andReturn($chatId = $this->faker()->uuid);

        $this->guzzle->shouldReceive('post')->andThrow(new RequestException('Invalid request',
            $this->mock(RequestInterface::class)));

        $this->telegram->sendMessage($receiver, $text);
    }
}
