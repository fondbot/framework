<?php

declare(strict_types=1);

namespace FondBot\Toolbelt\Commands;

use FondBot\Contracts\Queue;
use FondBot\Toolbelt\Command;
use Symfony\Component\Console\Input\InputOption;

class QueueWorker extends Command
{
    /** @var float */
    private $startTime;
    /** @var int */
    private $jobsHandled;

    protected function configure(): void
    {
        $this
            ->setName('queue:worker')
            ->setDescription('Run queue worker')
            ->addOption('timeout', 't', InputOption::VALUE_OPTIONAL, 'The number of seconds worker will run.')
            ->addOption('jobs', 'j', InputOption::VALUE_OPTIONAL, 'The number of jobs worker will process.');
    }

    /**
     * Handle command.
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    public function handle(): void
    {
        $this->startTime = microtime(true);
        $this->jobsHandled = 0;

        /** @var Queue $queue */
        $queue = resolve(Queue::class);

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

            if ($this->isTimeout() || $this->handledJobsInLimit()) {
                break;
            }
        }
    }

    private function isTimeout(): bool
    {
        $timeout = $this->getOption('timeout') ?? 60;

        return microtime(true) - $this->startTime > $timeout;
    }

    private function handledJobsInLimit(): bool
    {
        $this->jobsHandled++;
        $limit = (int) $this->getOption('jobs');

        return $limit <= 0 || $this->jobsHandled >= $limit;
    }
}
