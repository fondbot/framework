<?php

declare(strict_types=1);

namespace FondBot\Foundation\Commands;

use FondBot\Conversation\Context;
use FondBot\Conversation\ContextManager;
use Illuminate\Foundation\Bus\Dispatchable;

class SaveContext
{
    use Dispatchable;

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
