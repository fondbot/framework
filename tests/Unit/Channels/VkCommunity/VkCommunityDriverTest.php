<?php

declare(strict_types=1);

namespace Unit\Channels\VkCommunity;

use Tests\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use FondBot\Contracts\Channels\User;
use FondBot\Contracts\Database\Entities\Channel;
use FondBot\Channels\VkCommunity\VkCommunityUser;
use FondBot\Channels\VkCommunity\VkCommunityDriver;
use FondBot\Channels\VkCommunity\VkCommunityOutgoingMessage;
use FondBot\Channels\VkCommunity\VkCommunityReceivedMessage;

/**
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface guzzle
 * @property Channel                                    channel
 * @property VkCommunityDriver                          vkCommunity
 */
class VkCommunityDriverTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->guzzle = $this->mock(Client::class);
        $this->channel = $this->factory(Channel::class)->create([
            'driver' => VkCommunityDriver::class,
            'name' => $this->faker()->name,
            'parameters' => [
                'access_token' => str_random(),
                'confirmation_token' => str_random(),
            ],
        ]);

        $this->vkCommunity = new VkCommunityDriver($this->guzzle);
        $this->vkCommunity->setParameters($this->channel->parameters);
        $this->vkCommunity->setRequest([]);
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

        $result = $this->vkCommunity->getUser();

        $this->assertInstanceOf(User::class, $result);
        $this->assertInstanceOf(VkCommunityUser::class, $result);
        $this->assertEquals($senderId, $result->getId());
        $this->assertEquals($senderFirstName.' '.$senderLastName, $result->getName());
        $this->assertNull($result->getUsername());

        // Sender already set
        $result = $this->vkCommunity->getUser();

        $this->assertInstanceOf(User::class, $result);
        $this->assertEquals($senderId, $result->getId());
        $this->assertEquals($senderFirstName.' '.$senderLastName, $result->getName());
        $this->assertNull($result->getUsername());
    }

    public function test_sendMessage()
    {
        $recipient = $this->factory()->sender();
        $text = $this->faker()->text();

        $this->guzzle->shouldReceive('get')
            ->with(
                VkCommunityDriver::API_URL.'messages.send',
                [
                    'query' => [
                        'message' => $text,
                        'user_id' => $recipient->getId(),
                        'access_token' => $this->channel->parameters['access_token'],
                        'v' => VkCommunityDriver::API_VERSION,
                    ],
                ]
            )
            ->once();

        $result = $this->vkCommunity->sendMessage($recipient, $text);

        $this->assertInstanceOf(VkCommunityOutgoingMessage::class, $result);
        $this->assertSame($recipient, $result->getRecipient());
        $this->assertSame($text, $result->getText());
        $this->assertNull($result->getKeyboard());
    }

    public function test_getMessage()
    {
        $this->vkCommunity->setRequest([
            'type' => 'message_new',
            'object' => [
                'body' => $text = $this->faker()->word,
            ],
        ]);

        $message = $this->vkCommunity->getMessage();
        $this->assertInstanceOf(VkCommunityReceivedMessage::class, $message);
        $this->assertSame($text, $message->getText());
        $this->assertNull($message->getLocation());
        $this->assertNull($message->getAttachment());
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
