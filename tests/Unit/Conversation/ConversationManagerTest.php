<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Conversation;

use FondBot\Tests\TestCase;
use FondBot\Conversation\ConversationManager;

class ConversationManagerTest extends TestCase
{
    public function testRegisterIntent(): void
    {
        /** @var ConversationManager $manager */
        $manager = resolve(ConversationManager::class);

        $manager->registerIntent('foo');
        $manager->registerIntent('bar');

        $this->assertAttributeEquals(['foo', 'bar'], 'intents', $manager);

        $this->assertSame(['foo', 'bar'], $manager->getIntents());
    }
}
