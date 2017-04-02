<?php

declare(strict_types=1);

namespace Tests\Classes\Fakes;

use FondBot\Conversation\Intent;
use FondBot\Contracts\Conversation\Activator;
use FondBot\Contracts\Channels\Message\Attachment;
use VerbalExpressions\PHPVerbalExpressions\VerbalExpressions;

class FakeIntent extends Intent
{
    /**
     * Intent activators.
     *
     * @return Activator[]
     */
    public function activators(): array
    {
        return [
            $this->exact('/example'),
            $this->pattern('/\/example/'),
            $this->pattern((new VerbalExpressions())->startOfLine()->then('/example')->endOfLine()),
            $this->inArray(['/example']),
            $this->inArray(collect(['/example'])),
            $this->withAttachment(Attachment::TYPE_IMAGE),
            $this->withAttachment(),
        ];
    }

    /**
     * Process intent.
     */
    public function run(): void
    {
        $this->jump(FakeInteraction::class);
    }
}
