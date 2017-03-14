<?php
declare(strict_types=1);

namespace Tests\Unit\Conversation;

use FondBot\Channels\Driver;
use FondBot\Conversation\Context;
use FondBot\Conversation\Interaction;
use FondBot\Conversation\Story;
use Tests\TestCase;

/**
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface driver
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface story
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface interaction
 * @property array values
 * @property Context context
 */
class ContextTest extends TestCase
{

    protected function setUp()
    {
        parent::setUp();

        $this->driver = $this->mock(Driver::class);
        $this->story = $this->mock(Story::class);
        $this->interaction = $this->mock(Interaction::class);
        $this->values = [
            'key_1' => str_random(),
            'key_2' => str_random(),
        ];

        $this->context = new Context(
            $this->driver,
            $this->story,
            $this->interaction,
            $this->values
        );
    }

    public function test_driver()
    {
        $this->assertSame($this->driver, $this->context->getDriver());
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
    }

}