<?php

declare(strict_types=1);

namespace Tests\Unit\Channels\Drivers;

use Tests\TestCase;
use GuzzleHttp\Client;
use Illuminate\Http\File;
use FondBot\Conversation\Keyboard;
use FondBot\Contracts\Channels\Sender;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use FondBot\Contracts\Channels\Receiver;
use FondBot\Conversation\Keyboards\Button;
use GuzzleHttp\Exception\RequestException;
use FondBot\Channels\Telegram\TelegramDriver;
use FondBot\Channels\Telegram\TelegramMessage;
use FondBot\Contracts\Channels\Message\Location;
use FondBot\Contracts\Database\Entities\Channel;
use FondBot\Contracts\Channels\Message\Attachment;

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
        $this->telegram->setRequest(['message' => ['from' => $this->faker()->name]]);

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
        )->once();

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
                'text' => $text = $this->faker()->text,
            ],
        ]);

        /** @var TelegramMessage $message */
        $message = $this->telegram->getMessage();
        $this->assertInstanceOf(TelegramMessage::class, $message);
        $this->assertSame($text, $message->getText());
        $this->assertNull($message->getAttachment());
        $this->assertNull($message->getAudio());
        $this->assertNull($message->getDocument());
        $this->assertNull($message->getSticker());
        $this->assertNull($message->getVideo());
        $this->assertNull($message->getVoice());
        $this->assertNull($message->getContact());
        $this->assertNull($message->getLocation());
        $this->assertNull($message->getVenue());
    }

    /**
     * @dataProvider attachments
     *
     * @param string $type
     */
    public function test_getMessage_with_attachments(string $type)
    {
        $this->telegram->setRequest([
            'message' => [
                $type => [
                    'file_id' => $id = $this->faker()->uuid,
                ],
            ],
        ]);

        $response = $this->mock(ResponseInterface::class);
        $response->shouldReceive('getBody')->andReturnSelf();
        $response->shouldReceive('getContents')->andReturn(json_encode([
            'file_path' => $path = $this->faker()->imageUrl(),
        ]));

        // Get file path from Telegram
        $this->guzzle->shouldReceive('post')
            ->with(
                'https://api.telegram.org/bot'.$this->channel->parameters['token'].'/getFile',
                [
                    'form_params' => [
                        'file_id' => $id,
                    ],
                ]
            )
            ->andReturn($response)
            ->once();

        // Retrieve file contents
        $path = 'https://api.telegram.org/bot'.$this->channel->parameters['token'].'/'.$path;
        $response = $this->mock(ResponseInterface::class);
        $response->shouldReceive('getBody')->andReturnSelf();
        $response->shouldReceive('getContents')->andReturn($contents = $this->faker()->text);
        $this->guzzle->shouldReceive('get')->with($path)->andReturn($response)->once();

        $message = $this->telegram->getMessage();
        $this->assertInstanceOf(TelegramMessage::class, $message);

        $attachment = $this->telegram->getMessage()->getAttachment();
        $this->assertInstanceOf(Attachment::class, $attachment);
        $this->assertSame($type, $attachment->getType());
        $this->assertSame($path, $attachment->getPath());
        $this->assertSame($contents, $attachment->getContents());
        $this->assertInstanceOf(File::class, $attachment->getFile());
        $this->assertSame(['type' => $type, 'path' => $path], $attachment->toArray());
    }

    public function test_getMessage_with_contact_full()
    {
        $this->telegram->setRequest([
            'message' => [
                'contact' => $contact = [
                    'phone_number' => $phoneNumber = $this->faker()->phoneNumber,
                    'first_name' => $firstName = $this->faker()->firstName,
                    'last_name' => $lastName = $this->faker()->lastName,
                    'user_id' => $userId = $this->faker()->uuid,
                ],
            ],
        ]);

        /** @var TelegramMessage $message */
        $message = $this->telegram->getMessage();
        $this->assertInstanceOf(TelegramMessage::class, $message);
        $this->assertSame($contact, $message->getContact());

        $contact = $message->getContact();
        $this->assertSame($phoneNumber, $contact['phone_number']);
        $this->assertSame($firstName, $contact['first_name']);
        $this->assertSame($lastName, $contact['last_name']);
        $this->assertSame($userId, $contact['user_id']);
    }

    public function test_getMessage_with_contact_partial()
    {
        $this->telegram->setRequest([
            'message' => [
                'contact' => $contact = [
                    'phone_number' => $phoneNumber = $this->faker()->phoneNumber,
                    'first_name' => $firstName = $this->faker()->firstName,
                ],
            ],
        ]);

        $contact = array_merge($contact, ['last_name' => null, 'user_id' => null]);

        /** @var TelegramMessage $message */
        $message = $this->telegram->getMessage();
        $this->assertInstanceOf(TelegramMessage::class, $message);
        $this->assertSame($contact, $message->getContact());

        $contact = $message->getContact();
        $this->assertSame($phoneNumber, $contact['phone_number']);
        $this->assertSame($firstName, $contact['first_name']);
        $this->assertNull($contact['last_name']);
        $this->assertNull($contact['user_id']);
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
        $this->assertInstanceOf(TelegramMessage::class, $message);

        $location = $message->getLocation();
        $this->assertInstanceOf(Location::class, $location);
        $this->assertSame($latitude, $location->getLatitude());
        $this->assertSame($longitude, $location->getLongitude());
    }

    public function test_getMessage_with_venue_full()
    {
        $latitude = $this->faker()->latitude;
        $longitude = $this->faker()->longitude;

        $this->telegram->setRequest([
            'message' => [
                'text' => $this->faker()->text,
                'venue' => $venue = [
                    'location' => [
                        'latitude' => $latitude,
                        'longitude' => $longitude,
                    ],
                    'title' => $title = $this->faker()->title,
                    'address' => $address = $this->faker()->address,
                    'foursquare_id' => $foursquareId = $this->faker()->uuid,
                ],
            ],
        ]);

        $venue['location'] = new Location($venue['location']['latitude'], $venue['location']['longitude']);

        /** @var TelegramMessage $message */
        $message = $this->telegram->getMessage();
        $this->assertInstanceOf(TelegramMessage::class, $message);
        $this->assertEquals($venue, $message->getVenue());

        $venue = $message->getVenue();
        /** @var Location $location */
        $location = $venue['location'];
        $this->assertInstanceOf(Location::class, $location);
        $this->assertSame($latitude, $location->getLatitude());
        $this->assertSame($longitude, $location->getLongitude());
        $this->assertSame($title, $venue['title']);
        $this->assertSame($address, $venue['address']);
        $this->assertSame($foursquareId, $venue['foursquare_id']);
    }

    public function test_getMessage_with_venue_partial()
    {
        $latitude = $this->faker()->latitude;
        $longitude = $this->faker()->longitude;

        $this->telegram->setRequest([
            'message' => [
                'text' => $this->faker()->text,
                'venue' => $venue = [
                    'location' => [
                        'latitude' => $latitude,
                        'longitude' => $longitude,
                    ],
                    'title' => $title = $this->faker()->title,
                    'address' => $address = $this->faker()->address,
                ],
            ],
        ]);

        $venue['location'] = new Location($venue['location']['latitude'], $venue['location']['longitude']);
        $venue['foursquare_id'] = null;

        /** @var TelegramMessage $message */
        $message = $this->telegram->getMessage();
        $this->assertInstanceOf(TelegramMessage::class, $message);
        $this->assertEquals($venue, $message->getVenue());

        $venue = $message->getVenue();
        /** @var Location $location */
        $location = $venue['location'];
        $this->assertInstanceOf(Location::class, $location);
        $this->assertSame($latitude, $location->getLatitude());
        $this->assertSame($longitude, $location->getLongitude());
        $this->assertSame($title, $venue['title']);
        $this->assertSame($address, $venue['address']);
        $this->assertNull($venue['foursquare_id']);
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
        )->once();

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
        )->once();

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

    public function attachments(): array
    {
        return [
            ['audio'],
            ['document'],
            ['sticker'],
            ['video'],
            ['voice'],
        ];
    }
}
