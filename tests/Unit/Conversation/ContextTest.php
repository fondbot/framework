<?php

declare(strict_types=1);

namespace Tests\Unit\Conversation;

use FondBot\Contracts\Channels\Sender;
use FondBot\Contracts\Channels\SenderMessage;
use FondBot\Contracts\Database\Entities\Channel;
use Tests\TestCase;
use FondBot\Conversation\Story;
use FondBot\Conversation\Context;
use FondBot\Conversation\Interaction;

/**
 * @property \FondBot\Contracts\Database\Entities\Channel $channel
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface   $sender
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface   $message
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface   $story
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface   $interaction
 * @property array                                        $values
 * @property Context                                      $context
 */
class ContextTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->channel = new Channel();
        $this->sender = $this->mock(Sender::class);
        $this->message = $this->mock(SenderMessage::class);
        $this->story = $this->mock(Story::class);
        $this->interaction = $this->mock(Interaction::class);
        $this->values = [
            'key_1' => str_random(),
            'key_2' => str_random(),
        ];

        $this->context = new Context(
            $this->channel,
            $this->sender,
            $this->message,
            $this->story,
            $this->interaction,
            $this->values
        );
    }

    public function test_getChannel()
    {
        $this->assertSame($this->channel, $this->context->getChannel());
    }

    public function test_getSender()
    {
        $this->assertSame($this->sender, $this->context->getSender());
    }

    public function test_getMessage()
    {
        $this->assertSame($this->message, $this->context->getMessage());
    }

    public function test_story()
    {
        $this->assertSame($this->story, $this->context->getStory());

        $story = $this->mock(Story::class);

        $this->context->setStory($story);
        $this->assertSame($story, $this->context->getStory());
        $this->assertNotSame($this->story, $this->context->getStory());
    }

    public function test_interaction()
    {
        $this->assertSame($this->interaction, $this->context->getInteraction());

        $interaction = $this->mock(Interaction::class);

        $this->context->setInteraction($interaction);
        $this->assertSame($interaction, $this->context->getInteraction());
        $this->assertNotSame($this->interaction, $this->context->getInteraction());
    }

    public function test_values()
    {
        $this->assertSame($this->values, $this->context->getValues());

        $values = [
            'name' => $this->faker()->name,
            'phone' => $this->faker()->phoneNumber,
        ];

        $this->context->setValues($values);
        $this->assertSame($values, $this->context->getValues());
        $this->assertNotSame($this->values, $this->context->getValues());

        $this->context->setValue('uuid', $uuid = $this->faker()->uuid);
        $values['uuid'] = $uuid;
        $this->assertSame($values, $this->context->getValues());
    }

    public function test_toArray()
    {
        $expected = [
            'story' => get_class($this->story),
            'interaction' => get_class($this->interaction),
            'values' => $this->values,
        ];

        $this->assertSame($expected, $this->context->toArray());
    }
}
