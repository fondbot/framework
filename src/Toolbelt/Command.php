<?php

declare(strict_types=1);

namespace FondBot\Toolbelt;

use Symfony\Component\Console\Command\Command as SymfonyCommand;

abstract class Command extends SymfonyCommand
{
    protected $kernel;

    public function __construct(Kernel $kernel)
    {
        parent::__construct();

        $this->kernel = $kernel;
    }
}
