<?php

declare(strict_types=1);

namespace Tests\Unit\Conversation;

use Tests\TestCase;
use FondBot\Helpers\Str;
use FondBot\Conversation\Intent;
use FondBot\Conversation\Context;
use FondBot\Contracts\Channels\User;
use FondBot\Conversation\Interaction;
use FondBot\Contracts\Channels\ReceivedMessage;

/**
 * @property string                                     $channel
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface $sender
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface $message
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface $intent
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface $interaction
 * @property array                                      $values
 * @property Context                                    $context
 */
class ContextTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->channel = $this->faker()->userName;
        $this->sender = $this->mock(User::class);
        $this->message = $this->mock(ReceivedMessage::class);
        $this->intent = $this->mock(Intent::class);
        $this->interaction = $this->mock(Interaction::class);
        $this->values = [
            'foo' => Str::random(),
            'bar' => Str::random(),
        ];

        $this->context = new Context(
            $this->channel,
            $this->sender,
            $this->message,
            $this->intent,
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
        $this->assertSame($this->sender, $this->context->getUser());
    }

    public function test_getMessage()
    {
        $this->assertSame($this->message, $this->context->getMessage());
    }

    public function test_intent()
    {
        $this->assertSame($this->intent, $this->context->getIntent());

        $intent = $this->mock(Intent::class);

        $this->context->setIntent($intent);
        $this->assertSame($intent, $this->context->getIntent());
        $this->assertNotSame($this->intent, $this->context->getIntent());
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
            'intent' => get_class($this->intent),
            'interaction' => get_class($this->interaction),
            'values' => $this->values,
        ];

        $this->assertSame($expected, $this->context->toArray());
    }
}
