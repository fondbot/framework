<?php

declare(strict_types=1);

namespace FondBot\Toolbelt\Commands;

use FondBot\Toolbelt\Command;
use Symfony\Component\Process\Process;

class Log extends Command
{
    protected function configure(): void
    {
        $this
            ->setName('log')
            ->setDescription('Tail log');
    }

    /**
     * Handle command.
     *
     * @throws \Symfony\Component\Process\Exception\InvalidArgumentException
     * @throws \Symfony\Component\Process\Exception\LogicException
     * @throws \Symfony\Component\Process\Exception\RuntimeException
     * @throws \Psr\Container\ContainerExceptionInterface
     */
    public function handle(): void
    {
        $path = $this->kernel->resolve('application_log');
        $command = 'tail -f -n 1000 '.escapeshellarg($path);

        (new Process($command))->setTimeout(null)->run(function ($type, $line) {
            $this->line($line);
        });
    }
}
