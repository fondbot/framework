<?php

declare(strict_types=1);

namespace Tests\Unit\Conversation\Providers;

use FondBot\Tests\TestCase;
use FondBot\Application\Config;
use FondBot\Conversation\Intent;
use FondBot\Conversation\IntentManager;
use FondBot\Conversation\FallbackIntent;
use FondBot\Conversation\Activators\Activator;
use FondBot\Conversation\Providers\IntentServiceProvider;

class IntentServiceProviderTest extends TestCase
{
    public function test(): void
    {
        $this->container->add(TestIntent::class, new TestIntent());
        $this->container->add(FallbackIntent::class, new FallbackIntent());

        $this->container->add(Config::class, function () use (&$intent1, &$intent2) {
            $config = new Config();

            $config->set('intents', [TestIntent::class]);
            $config->set('fallback_intent', FallbackIntent::class);

            return $config;
        });

        $this->container->addServiceProvider(new IntentServiceProvider());

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
