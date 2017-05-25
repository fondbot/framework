<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Conversation;

use FondBot\Tests\TestCase;
use FondBot\Conversation\ConversationManager;
use FondBot\Conversation\ConversationServiceProvider;

class ConversationServiceProviderTest extends TestCase
{
    public function test(): void
    {
        $this->container->addServiceProvider(new ConversationServiceProvider);

        $this->assertInstanceOf(ConversationManager::class, $this->container->get(ConversationManager::class));
    }
}
