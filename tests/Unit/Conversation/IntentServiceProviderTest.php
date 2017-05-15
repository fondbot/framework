<?php

declare(strict_types=1);

namespace Tests\Unit\Conversation;

use FondBot\Tests\TestCase;
use FondBot\Conversation\Intent;
use FondBot\Conversation\IntentManager;
use FondBot\Conversation\FallbackIntent;
use FondBot\Conversation\Activators\Activator;
use FondBot\Conversation\IntentServiceProvider;

class IntentServiceProviderTest extends TestCase
{
    public function test(): void
    {
        $provider = $this->mock(IntentServiceProvider::class)->makePartial();
        $provider->shouldReceive('intents')->andReturn([TestIntent::class])->once();
        $provider->shouldReceive('fallbackIntent')->andReturn(FallbackIntent::class)->once();

        $this->container->add(TestIntent::class, new TestIntent());
        $this->container->add(FallbackIntent::class, new FallbackIntent());
        $this->container->addServiceProvider($provider);

        /** @var IntentManager $manager */
        $manager = $this->container->get(IntentManager::class);

        $this->assertInstanceOf(IntentManager::class, $manager);
    }
}

class TestIntent extends Intent
{
    /**
     * Intent activators.
     *
     * @return Activator[]
     */
    public function activators(): array
    {
        return [];
    }

    /**
     * Run intent.
     */
    public function run(): void
    {
    }
}
