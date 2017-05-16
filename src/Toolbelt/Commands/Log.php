<?php

declare(strict_types=1);

namespace FondBot\Toolbelt\Commands;

use Monolog\Logger;
use FondBot\Toolbelt\Command;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\HandlerInterface;
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
        /** @var Logger $logger */
        $logger = $this->kernel->resolve(Logger::class);

        /** @var StreamHandler|null $handler */
        $handler = collect($logger->getHandlers())->first(function (HandlerInterface $item) {
            return $item instanceof StreamHandler;
        });

        if ($handler === null) {
            $this->error('There are no logs stored in filesystem.');
            exit;
        }

        $command = 'tail -f -n 1000 '.escapeshellarg($handler->getUrl());

        (new Process($command))->setTimeout(null)->run(function ($type, $line) {
            $this->line($line);
        });
    }
}
