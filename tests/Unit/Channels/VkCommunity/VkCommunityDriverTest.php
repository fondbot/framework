<?php

declare(strict_types=1);

namespace Unit\Channels\VkCommunity;

use FondBot\Channels\Message;
use FondBot\Channels\VkCommunity\VkCommunityDriver;
use FondBot\Contracts\Database\Entities\Channel;
use GuzzleHttp\Client;
use Tests\TestCase;

/**
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface guzzle
 * @property Channel channel
 * @property VkCommunityDriverTest vkCommunity
 */
class VkCommunityDriverTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->guzzle = $this->mock(Client::class);
        $this->channel = new Channel([
            'driver'     => VkCommunityDriver::class,
            'name'       => $this->faker()->name,
            'parameters' => [
                'access_token'       => str_random(),
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
            'type'   => 'message_new',
            'object' => [
                'user_id' => str_random(),
                'body'    => $this->faker()->word
            ]
        ]);

        $this->vkCommunity->verifyRequest();
    }

    /*
     * @group ignore
     */
    public function test_getSender()
    {
        //
    }

    /*
     * @group ignore
     */
    public function test_sendMessage()
    {
        //
    }

    public function test_getMessage()
    {
        $this->vkCommunity->setRequest([
            'type'   => 'message_new',
            'object' => [
                'body' => $this->faker()->word
            ]
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
    }

}
