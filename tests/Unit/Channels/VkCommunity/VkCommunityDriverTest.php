<?php

declare(strict_types=1);

namespace Unit\Channels\VkCommunity;

use Tests\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use FondBot\Contracts\Channels\User;
use FondBot\Channels\VkCommunity\VkCommunityUser;
use FondBot\Channels\VkCommunity\VkCommunityDriver;
use FondBot\Channels\VkCommunity\VkCommunityOutgoingMessage;
use FondBot\Channels\VkCommunity\VkCommunityReceivedMessage;

/**
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface guzzle
 * @property array                                      parameters
 * @property VkCommunityDriver                          vkCommunity
 */
class VkCommunityDriverTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->guzzle = $this->mock(Client::class);
        $this->vkCommunity = new VkCommunityDriver($this->guzzle);
        $this->vkCommunity->fill($this->parameters = [
            'access_token' => str_random(),
            'confirmation_token' => str_random(),
        ]);
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
        $this->vkCommunity->fill($this->parameters, ['type' => 'fake']);

        $this->vkCommunity->verifyRequest();
    }

    /**
     * @expectedException \FondBot\Channels\Exceptions\InvalidChannelRequest
     * @expectedExceptionMessage Invalid object
     */
    public function test_verifyRequest_empty_object()
    {
        $this->vkCommunity->fill($this->parameters, ['type' => 'message_new']);

        $this->vkCommunity->verifyRequest();
    }

    /**
     * @expectedException \FondBot\Channels\Exceptions\InvalidChannelRequest
     * @expectedExceptionMessage Invalid user_id
     */
    public function test_verifyRequest_empty_object_user_id()
    {
        $this->vkCommunity->fill($this->parameters,
            ['type' => 'message_new', 'object' => ['body' => $this->faker()->word]]);

        $this->vkCommunity->verifyRequest();
    }

    /**
     * @expectedException \FondBot\Channels\Exceptions\InvalidChannelRequest
     * @expectedExceptionMessage Invalid body
     */
    public function test_verifyRequest_empty_object_body()
    {
        $this->vkCommunity->fill($this->parameters, ['type' => 'message_new', 'object' => ['user_id' => str_random()]]);

        $this->vkCommunity->verifyRequest();
    }

    public function test_verifyRequest()
    {
        $this->vkCommunity->fill($this->parameters, [
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

        $this->vkCommunity->fill($this->parameters, [
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
        $recipient = $this->mock(User::class);
        $recipient->shouldReceive('getId')->andReturn($recipientId = $this->faker()->uuid)->atLeast()->once();
        $text = $this->faker()->text();

        $this->guzzle->shouldReceive('get')
            ->with(
                VkCommunityDriver::API_URL.'messages.send',
                [
                    'query' => [
                        'message' => $text,
                        'user_id' => $recipientId,
                        'access_token' => $this->parameters['access_token'],
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
        $this->vkCommunity->fill($this->parameters, [
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
        $this->vkCommunity->fill($this->parameters, ['type' => 'confirmation']);

        $this->assertTrue($this->vkCommunity->isVerificationRequest());
    }

    public function test_verifyWebhook()
    {
        $this->assertEquals(
            $this->parameters['confirmation_token'],
            $this->vkCommunity->getParameter('confirmation_token')
        );

        $this->vkCommunity->verifyWebhook();
    }
}
