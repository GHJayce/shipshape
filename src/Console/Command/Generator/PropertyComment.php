<?php

declare(strict_types=1);

namespace Ghjayce\Shipshape\Console\Command\Generator;

use Ghjayce\Shipshape\Console\Command\Command;
use Ghjayce\Shipshape\Console\Command\Generator\PropertyComment\Original\PropertyCommentOriginal;
use Ghjayce\Shipshape\Entity\Config\ShipshapeConfig;
use PhpParser\Lexer\Emulative;
use PhpParser\Parser\Php7;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

class PropertyComment extends Command
{
    private string $propertyClassWithNamespace = 'Ghjayce\Shipshape\Entity\Base\Property';

    public function __construct(?string $name = null)
    {
        parent::__construct('generator:propertyComment');
    }

    public function configure(): void
    {
        parent::configure();
        $this->addArgument('target', InputArgument::REQUIRED, 'file absolute path or class name with namespace or scan the directory files.');
        $this->addArgument('ignoreClasses', InputArgument::OPTIONAL, 'ignore handle some class name with namespace.');
    }

    public function handle(InputInterface $input, OutputInterface $output): void
    {
        $target = $input->getArgument('target');
        $ignoreClasses = PropertyCommentOriginal::getIgnoreClasses([
            ShipshapeConfig::class,
            ...explode(',', $input->getArgument('ignoreClasses') ?? ''),
        ]);
        if (!$target) {
            throw new \RuntimeException('Target argument is empty.');
        }


        $this->modeOriginal($target, $ignoreClasses);
    }

    private function modeOriginal(string $target, array $ignoreClasses): void
    {

        $propertyCommentOriginal = new PropertyCommentOriginal();
        $propertyCommentOriginal->handle($target, $ignoreClasses);
    }
}
