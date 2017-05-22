<?php

declare(strict_types=1);

namespace FondBot\Toolbelt\Commands;

use FondBot\Contracts\Queue;
use FondBot\Toolbelt\Command;

class QueueWorker extends Command
{
    protected function configure(): void
    {
        $this
            ->setName('queue:worker')
            ->setDescription('Run queue worker');
    }

    /**
     * Handle command.
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     */
    public function handle(): void
    {
        /** @var Queue $queue */
        $queue = $this->kernel->resolve(Queue::class);

        $this->info('Worker started...');

        while (true) {
            $job = $queue->next();

            if ($job === null) {
                continue;
            }

            $this->line('Job: '.get_class($job));

            $driver = $job->driver;
            $command = $job->command;

            $driver->handle($command);
        }
    }
}
