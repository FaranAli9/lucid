<?php

namespace Lucid\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class LucidCommand extends Command
{
    protected InputInterface $input;

    protected OutputInterface $output;

    protected string $name;

    protected string $description;

    public function info($message): void
    {
        $this->output->writeln("<info>$message</info>");
    }

    public function error($message): void
    {
        $this->output->writeln("<error>$message</error>");
    }

    protected function configure(): void
    {
        $this->setName($this->name);
        $this->setDescription($this->description);

        foreach ($this->getArguments() as $arguments) {
            call_user_func_array([$this, 'addArgument'], $arguments);
        }
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->input  = $input;
        $this->output = $output;

        return (int) $this->handle();
    }

    protected function argument($name)
    {
        return $this->input->getArgument($name);
    }

    abstract public function handle(): int;
}
