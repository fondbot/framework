<?php

declare(strict_types=1);

namespace Tests\Classes;

use FondBot\Helpers\Str;
use FondBot\Application\Kernel;
use FondBot\Drivers\ReceivedMessage;
use FondBot\Conversation\Interaction;

class TestInteraction extends Interaction
{
    /**
     * Run interaction.
     */
    public function run(): void
    {
    }

    public function runIncorrect(Kernel $kernel): void
    {
        $this->kernel = $kernel;

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
