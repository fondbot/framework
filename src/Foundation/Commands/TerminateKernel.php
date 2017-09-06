<?php

declare(strict_types=1);

namespace FondBot\Foundation\Commands;

use FondBot\Foundation\Kernel;
use Illuminate\Foundation\Bus\Dispatchable;

class TerminateKernel
{
    use Dispatchable;

    public function handle(Kernel $kernel): void
    {
        // TODO

        // Close session if conversation has not been transitioned
        // Otherwise, save session state
//        if (!$this->transitioned) {
//            kernel()->closeSession();
//            kernel()->clearContext();
//        }

        // Save session if exists
        if ($session = $kernel->getSession()) {
            SaveSession::dispatch($session);
        }

        // Save context if exists
        if ($context = $kernel->getContext()) {
            SaveContext::dispatch($context);
        }
    }
}
