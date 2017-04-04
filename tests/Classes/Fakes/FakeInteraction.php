<?php

declare(strict_types=1);

namespace Tests\Classes\Fakes;

use FondBot\Bot;
use FondBot\Helpers\Str;
use FondBot\Drivers\ReceivedMessage;
use FondBot\Conversation\Interaction;

class FakeInteraction extends Interaction
{
    /**
     * Run interaction.
     */
    public function run(): void
    {
    }

    public function runIncorrect(Bot $bot): void
    {
        $this->bot = $bot;

        $this->jump(Str::random());
    }

    /**
     * Process received message.
     *
     * @param ReceivedMessage $reply
     */
    public function process(ReceivedMessage $reply): void
    {
        $this->remember('key', 'value');
    }
}
