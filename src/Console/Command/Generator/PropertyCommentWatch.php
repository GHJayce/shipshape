<?php

declare(strict_types=1);

namespace Ghjayce\Shipshape\Console\Command\Generator;

use Ghjayce\Shipshape\Console\Command\Command;
use Ghjayce\Shipshape\Console\Command\Generator\PropertyComment\PropertyCommentService;
use Ghjayce\Shipshape\Console\Command\Generator\PropertyCommentWatch\Entity\PcwConfig;
use Ghjayce\Shipshape\Console\Command\Generator\PropertyCommentWatch\Entity\PcwContext;
use Ghjayce\Shipshape\Console\Command\Generator\PropertyCommentWatch\Entity\PcwParam;
use Ghjayce\Shipshape\Console\Command\Generator\PropertyCommentWatch\Service\PcwService;
use Ghjayce\Shipshape\Entity\Config\ShipshapeConfig;
use Ghjayce\Shipshape\Tool\ClassTool;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class PropertyCommentWatch extends Command
{
    public function __construct(?string $name = null)
    {
        parent::__construct('generator:propertyCommentWatch');
    }

    public function configure(): void
    {
        parent::configure();
        $this->addArgument('target', InputArgument::REQUIRED, 'scan the directory files.');
        $this->addOption('ignoreClasses', 'ic', InputOption::VALUE_OPTIONAL, 'ignore handle some class name with namespace, multiple comma-separated.');
        $this->addOption('intervalMin', 'iMin', InputOption::VALUE_OPTIONAL, 'minimum interval scan time.', 5);
        $this->addOption('intervalMax', 'iMax', InputOption::VALUE_OPTIONAL, 'maximum interval scan time.', 600);
    }

    public function handle(InputInterface $input, OutputInterface $output): void
    {
        $target = $input->getArgument('target');
        if (!$target) {
            throw new \RuntimeException('Target argument is empty.');
        }
        $ignoreClasses = PropertyCommentService::getIgnoreClasses([
            ShipshapeConfig::class,
            ...explode(',', $input->getOption('ignoreClasses') ?? ''),
        ]);

        $param = PcwParam::make()
            ->setTarget($target)
            ->setIgnoreClassesWithNamespace($ignoreClasses)
            ->setClassLoader(ClassTool::findLoader())
            ->setIntervalMin($input->getOption('intervalMin'))
            ->setIntervalMax($input->getOption('intervalMax'));
        $this->main($param);
    }

    public function main(PcwParam $param): void
    {
        echo "Listening to the directory: {$param->getTarget()}", "\n";
        $context = PcwContext::make()
            ->setService(new PcwService());
        $config = PcwConfig::make();
        while (true) {
            $context->setParam($param);
            $result = $this->shipshapeExecute($config, $context);
            $param->setLastScanResult($result['scanResult'])
                ->setInterval($result['interval']);
            $interval = $result['interval'] ?? $param->getIntervalMin();
            echo 'Next execution time: '. date('Y-m-d H:i:s', time() + $interval), "\n";
            sleep($interval);
        }
    }
}
