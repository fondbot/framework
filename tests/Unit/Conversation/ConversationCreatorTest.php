<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Conversation;

use FondBot\Tests\TestCase;
use FondBot\Conversation\ConversationCreator;

class ConversationCreatorTest extends TestCase
{
    public function testCreateIntent(): void
    {
        $this->filesystem()->put('vendor/fondbot/framework/resources/stubs/Intent.stub', file_get_contents('resources/stubs/Intent.stub'));

        (new ConversationCreator($this->filesystem()))->createIntent('foo', 'bar', 'baz');

        $this->assertTrue($this->filesystem()->exists('foo/Intents/BazIntent.php'));
    }

    public function testCreateInteraction(): void
    {
        $this->filesystem()->put('vendor/fondbot/framework/resources/stubs/Interaction.stub', file_get_contents('resources/stubs/Interaction.stub'));

        (new ConversationCreator($this->filesystem()))->createInteraction('foo', 'bar', 'baz');

        $this->assertTrue($this->filesystem()->exists('foo/Interactions/BazInteraction.php'));
    }
}
