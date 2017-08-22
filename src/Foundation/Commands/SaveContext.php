<?php

declare(strict_types=1);

namespace FondBot\Foundation\Commands;

use FondBot\Conversation\Context;
use FondBot\Conversation\ContextManager;

class SaveContext
{
    private $context;

    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    public function handle(ContextManager $manager): void
    {
        $manager->save($this->context);
    }
}
