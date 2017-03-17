<?php

declare(strict_types=1);

namespace Unit\Channels\VkCommunity;

use FondBot\Channels\Message;
use FondBot\Channels\Receiver;
use FondBot\Channels\Sender;
use FondBot\Channels\VkCommunity\VkCommunityDriver;
use FondBot\Contracts\Database\Entities\Channel;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Tests\TestCase;

/**
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface guzzle
 * @property Channel channel
 * @property VkCommunityDriver vkCommunity
 */
class VkCommunityDriverTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->guzzle = $this->mock(Client::class);
        $this->channel = new Channel([
            'driver' => VkCommunityDriver::class,
            'name' => $this->faker()->name,
            'parameters' => [
                'access_token' => str_random(),
                'confirmation_token' => str_random(),
            ],
        ]);

        $this->vkCommunity = new VkCommunityDriver($this->guzzle);
        $this->vkCommunity->setChannel($this->channel);
        $this->vkCommunity->setRequest([]);
    }

    public function test_getChannel()
    {
        $this->assertSame($this->channel, $this->vkCommunity->getChannel());
    }

    public function test_getConfig()
    {
        $expected = ['access_token', 'confirmation_token'];

        $this->assertSame($expected, $this->vkCommunity->getConfig());
    }

    /**
     * @expectedException \FondBot\Channels\Exceptions\InvalidChannelRequest
     * @expectedExceptionMessage Invalid type
     */
    public function test_verifyRequest_error_type()
    {
        $this->vkCommunity->setRequest(['type' => 'fake']);

        $this->vkCommunity->verifyRequest();
    }

    /**
     * @expectedException \FondBot\Channels\Exceptions\InvalidChannelRequest
     * @expectedExceptionMessage Invalid object
     */
    public function test_verifyRequest_empty_object()
    {
        $this->vkCommunity->setRequest(['type' => 'message_new']);

        $this->vkCommunity->verifyRequest();
    }

    /**
     * @expectedException \FondBot\Channels\Exceptions\InvalidChannelRequest
     * @expectedExceptionMessage Invalid user_id
     */
    public function test_verifyRequest_empty_object_user_id()
    {
        $this->vkCommunity->setRequest(['type' => 'message_new', 'object' => ['body' => $this->faker()->word]]);

        $this->vkCommunity->verifyRequest();
    }

    /**
     * @expectedException \FondBot\Channels\Exceptions\InvalidChannelRequest
     * @expectedExceptionMessage Invalid body
     */
    public function test_verifyRequest_empty_object_body()
    {
        $this->vkCommunity->setRequest(['type' => 'message_new', 'object' => ['user_id' => str_random()]]);

        $this->vkCommunity->verifyRequest();
    }

    public function test_verifyRequest()
    {
        $this->vkCommunity->setRequest([
            'type' => 'message_new',
            'object' => [
                'user_id' => str_random(),
                'body' => $this->faker()->word,
            ],
        ]);

        $this->vkCommunity->verifyRequest();
    }

    public function test_getSender()
    {
        $userId = random_int(1, time());
        $senderId = $this->faker()->uuid;
        $senderFirstName = $this->faker()->firstName;
        $senderLastName = $this->faker()->lastName;

        $response = new Response(200, [], json_encode([
            'response' => [
                [
                    'id' => $senderId,
                    'first_name' => $senderFirstName,
                    'last_name' => $senderLastName,
                ],
            ],
        ]));

        $this->guzzle->shouldReceive('get')
            ->with(
                VkCommunityDriver::API_URL.'users.get',
                [
                    'query' => [
                        'user_ids' => $userId,
                        'v' => VkCommunityDriver::API_VERSION,
                    ],
                ]
            )
            ->once()
            ->andReturn($response);

        $this->vkCommunity->setRequest([
            'object' => [
                'user_id' => $userId,
            ],
        ]);

        $result = $this->vkCommunity->getSender();

        $this->assertInstanceOf(Sender::class, $result);
        $this->assertEquals($senderId, $result->getIdentifier());
        $this->assertEquals($senderFirstName.' '.$senderLastName, $result->getName());
        $this->assertNull($result->getUsername());

        // Sender already set
        $result = $this->vkCommunity->getSender();

        $this->assertInstanceOf(Sender::class, $result);
        $this->assertEquals($senderId, $result->getIdentifier());
        $this->assertEquals($senderFirstName.' '.$senderLastName, $result->getName());
        $this->assertNull($result->getUsername());
    }

    public function test_sendMessage()
    {
        $receiver = Receiver::create($this->faker()->uuid, $this->faker()->name);
        $text = $this->faker()->text();

        $this->guzzle->shouldReceive('get')
            ->with(
                VkCommunityDriver::API_URL.'messages.send',
                [
                    'query' => [
                        'message' => $text,
                        'user_id' => $receiver->getIdentifier(),
                        'access_token' => $this->channel->parameters['access_token'],
                        'v' => VkCommunityDriver::API_VERSION,
                    ],
                ]
            )
            ->once();

        $this->vkCommunity->sendMessage($receiver, $text);
    }

    public function test_getMessage()
    {
        $this->vkCommunity->setRequest([
            'type' => 'message_new',
            'object' => [
                'body' => $this->faker()->word,
            ],
        ]);

        $this->assertInstanceOf(Message::class, $this->vkCommunity->getMessage());
    }

    public function test_isVerificationRequest()
    {
        $this->vkCommunity->setRequest(['type' => 'confirmation']);

        $this->assertTrue($this->vkCommunity->isVerificationRequest());
    }

    public function test_verifyWebhook()
    {
        $this->assertEquals(
            $this->channel->parameters['confirmation_token'],
            $this->vkCommunity->getParameter('confirmation_token')
        );

        $this->vkCommunity->verifyWebhook();
    }
}
