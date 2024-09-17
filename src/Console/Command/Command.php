<?php

declare(strict_types=1);

namespace Ghjayce\Shipshape\Console\Command;

use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class Command extends SymfonyCommand
{
    abstract public function handle(InputInterface $input, OutputInterface $output): void;

    public function execute(InputInterface $input, OutputInterface $output): int
    {
//        try {
            $this->handle($input, $output);
//        } catch (\Throwable $exception) {
//            $output->writeln($exception->getMessage());
//        }
        return 0;
    }
}
