<?php
declare(strict_types=1);

namespace Tests\Unit\Channels\Drivers;

use FondBot\Channels\Drivers\Telegram;
use FondBot\Channels\Objects\Message;
use FondBot\Channels\Objects\Participant;
use FondBot\Conversation\Keyboard;
use FondBot\Conversation\Keyboards\Button;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\RequestInterface;
use Tests\TestCase;

/**
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface guzzle
 * @property string channelName
 * @property Telegram telegram
 */
class TelegramTest extends TestCase
{

    protected function setUp()
    {
        parent::setUp();

        $this->guzzle = $this->mock(Client::class);
        $this->channelName = $this->faker()->name;

        $this->telegram = new Telegram(
            $this->channelName,
            ['token' => str_random()],
            $this->guzzle
        );
    }

    public function test_getChannelName()
    {
        $this->assertEquals($this->channelName, $this->telegram->getChannelName());
    }

    public function test_getConfig()
    {
        $expected = ['token'];

        $this->assertEquals($expected, $this->telegram->getConfig());
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

        $this->guzzle->shouldReceive('post')->with('setWebhook', [
            'form_params' => [
                'url' => $url,
            ],
        ]);

        $this->telegram->installWebhook($url);
    }

    public function test_participant()
    {
        $this->telegram->setRequest([
            'message' => [
                'from' => [
                    'id' => str_random(),
                    'first_name' => $this->faker()->firstName,
                    'last_name' => $this->faker()->lastName,
                    'username' => $this->faker()->userName,
                ],
            ]
        ]);

        $this->assertInstanceOf(Participant::class, $this->telegram->getParticipant());
    }

    public function test_message()
    {
        $this->telegram->setRequest([
            'message' => [
                'text' => $this->faker()->text,
            ]
        ]);

        $this->assertInstanceOf(Message::class, $this->telegram->getMessage());
    }

    public function test_reply_with_keyboard()
    {
        $participant = $this->mock(Participant::class);
        $message = $this->mock(Message::class);
        $keyboard = $this->mock(Keyboard::class);
        $button1 = $this->mock(Button::class);
        $button2 = $this->mock(Button::class);

        $participant->shouldReceive('getIdentifier')->andReturn($chatId = $this->faker()->uuid);
        $message->shouldReceive('getText')->andReturn($text = $this->faker()->text);
        $keyboard->shouldReceive('buttons')->andReturn([$button1, $button2]);
        $button1->shouldReceive('value')->andReturn($button1Text = $this->faker()->word);
        $button2->shouldReceive('value')->andReturn($button2Text = $this->faker()->word);

        $replyMarkup = json_encode([
            'keyboard' => [
                [
                    (object)['text' => $button1Text],
                    (object)['text' => $button2Text],
                ]
            ],
            'resize_keyboard' => true,
        ]);

        $this->guzzle->shouldReceive('post')->with('sendMessage', [
            'form_params' => [
                'chat_id' => $chatId,
                'text' => $text,
                'reply_markup' => $replyMarkup,
            ],
        ]);

        $this->telegram->reply($participant, $message, $keyboard);
    }

    public function test_reply_without_keyboard()
    {
        $participant = $this->mock(Participant::class);
        $message = $this->mock(Message::class);

        $participant->shouldReceive('getIdentifier')->andReturn($chatId = $this->faker()->uuid);
        $message->shouldReceive('getText')->andReturn($text = $this->faker()->text);

        $this->guzzle->shouldReceive('post')->with('sendMessage', [
            'form_params' => [
                'chat_id' => $chatId,
                'text' => $text,
            ],
        ]);

        $this->telegram->reply($participant, $message);
    }

    public function test_reply_request_exception()
    {
        $participant = $this->mock(Participant::class);
        $message = $this->mock(Message::class);

        $participant->shouldReceive('getIdentifier')->andReturn($chatId = $this->faker()->uuid);
        $message->shouldReceive('getText')->andReturn($text = $this->faker()->text);

        $this->guzzle->shouldReceive('post')->with('sendMessage', [
            'form_params' => [
                'chat_id' => $chatId,
                'text' => $text,
            ],
        ])->andThrow(new RequestException('Invalid request', $this->mock(RequestInterface::class)));

        $this->telegram->reply($participant, $message);
    }

}