<?php

declare(strict_types=1);

namespace FondBot\Toolbelt;

use League\Flysystem\Filesystem;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

abstract class Command extends SymfonyCommand
{
    protected $kernel;

    /** @var InputInterface */
    protected $input;

    /** @var SymfonyStyle */
    protected $output;

    public function __construct(Kernel $kernel)
    {
        parent::__construct();

        $this->kernel = $kernel;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = new SymfonyStyle($input, $output);

        $this->handle();
    }

    /**
     * Get argument value.
     *
     * @param string $name
     *
     * @return mixed
     *
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    protected function getArgument(string $name)
    {
        return $this->input->getArgument($name);
    }

    /**
     * Write a message.
     *
     * @param string $message
     */
    protected function line(string $message): void
    {
        $this->output->writeln($message);
    }

    /**
     * Display success message.
     *
     * @param string $message
     */
    protected function success(string $message): void
    {
        $this->output->success($message);
    }

    /**
     * Display info message.
     *
     * @param string $message
     */
    protected function info(string $message): void
    {
        $this->output->writeln('<info>'.$message.'</info>');
    }

    /**
     * Display warning message.
     *
     * @param string $message
     */
    protected function warning(string $message): void
    {
        $this->output->writeln('<warning>'.$message.'</warning>');
    }

    /**
     * Display error message.
     *
     * @param string $message
     */
    protected function error(string $message): void
    {
        $this->output->writeln('<error>'.$message.'</error>');
    }

    /**
     * Prompt the user for input.
     *
     * @param string $message
     *
     * @return string
     */
    protected function input(string $message): string
    {
        return $this->output->ask($message);
    }

    /**
     * Display confirmation input.
     *
     * @param string $message
     *
     * @return bool
     */
    protected function confirm(string $message): bool
    {
        return $this->output->confirm($message, false);
    }

    /**
     * Get filesystem instance.
     *
     * @return Filesystem
     */
    protected function filesystem(): Filesystem
    {
        return $this->kernel->resolve(Filesystem::class);
    }

    /**
     * Handle command.
     */
    abstract public function handle(): void;
}
