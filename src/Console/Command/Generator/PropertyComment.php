<?php

declare(strict_types=1);

namespace Ghjayce\Shipshape\Console\Command\Generator;

use Ghjayce\Shipshape\Console\Command\Command;
use Ghjayce\Shipshape\Console\Command\Generator\PropertyComment\ModeA\Entity\ModeAConfig;
use Ghjayce\Shipshape\Console\Command\Generator\PropertyComment\ModeA\Entity\ModeAContext;
use Ghjayce\Shipshape\Console\Command\Generator\PropertyComment\ModeA\Entity\ModeAParam;
use Ghjayce\Shipshape\Console\Command\Generator\PropertyComment\ModeB\Entity\ModeBConfig;
use Ghjayce\Shipshape\Console\Command\Generator\PropertyComment\ModeB\Entity\ModeBContext;
use Ghjayce\Shipshape\Console\Command\Generator\PropertyComment\ModeB\Entity\ModeBParam;
use Ghjayce\Shipshape\Console\Command\Generator\PropertyComment\ModeB\Service\ModeBService;
use Ghjayce\Shipshape\Console\Command\Generator\PropertyComment\ModeC\Entity\ModeCConfig;
use Ghjayce\Shipshape\Console\Command\Generator\PropertyComment\ModeC\Entity\ModeCContext;
use Ghjayce\Shipshape\Console\Command\Generator\PropertyComment\ModeC\Entity\ModeCParam;
use Ghjayce\Shipshape\Console\Command\Generator\PropertyComment\ModeC\Service\ModeCService;
use Ghjayce\Shipshape\Console\Command\Generator\PropertyComment\Original\PropertyCommentOriginal;
use Ghjayce\Shipshape\Console\Command\Generator\PropertyComment\PropertyCommentService;
use Ghjayce\Shipshape\Entity\Config\ShipshapeConfig;
use Ghjayce\Shipshape\Entity\Context\ClientContext;
use Ghjayce\Shipshape\Entity\Context\ShipshapeContext;
use Ghjayce\Shipshape\Shipshape;
use Ghjayce\Shipshape\Tool\ClassTool;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PropertyComment extends Command
{
    protected Shipshape $shipshape;

    public function __construct(?string $name = null)
    {
        parent::__construct('generator:propertyComment');
        $this->shipshape = new Shipshape();
    }

    public function configure(): void
    {
        parent::configure();
        $this->addArgument('target', InputArgument::REQUIRED, 'file absolute path or class name with namespace or scan the directory files.');
        $this->addArgument('ignoreClasses', InputArgument::OPTIONAL, 'ignore handle some class name with namespace, multiple comma-separated.');
    }

    public function handle(InputInterface $input, OutputInterface $output): void
    {
        $target = $input->getArgument('target');
        $ignoreClasses = PropertyCommentService::getIgnoreClasses([
            ShipshapeConfig::class,
            ...explode(',', $input->getArgument('ignoreClasses') ?? ''),
        ]);
        if (!$target) {
            throw new \RuntimeException('Target argument is empty.');
        }

        // The following is the realization of different writing methods.
        // You can remove comments and try them one by one.

        // Native writing.
        //$this->modeOriginal($target, $ignoreClasses);

        // Use Shipshape.
        //$this->modeA($target, $ignoreClasses);
        $this->modeB($target, $ignoreClasses);
        //$this->modeC($target, $ignoreClasses);
    }

    /**
     * @throws \JsonException
     */
    private function modeOriginal(string $target, array $ignoreClasses): void
    {
        $propertyCommentOriginal = new PropertyCommentOriginal();
        $propertyCommentOriginal->handle($target, $ignoreClasses);
    }

    /**
     * @param ShipshapeConfig $config
     * @param ClientContext $clientContext
     * @return mixed
     */
    private function shipshapeExecute(ShipshapeConfig $config, ClientContext $clientContext): mixed
    {
        $method = __METHOD__;
        $config->setAfterHandleHook(function (ShipshapeConfig $config, ShipshapeContext $context) use ($method) {
            //var_dump([$method, $context->getActionName()]);
        });
        return $this->shipshape->execute($config->build(), ShipshapeContext::make()->setClientContext($clientContext));
    }

    private function modeA(string $target, array $ignoreClasses): void
    {
        $param = ModeAParam::make()
            ->setTarget($target)
            ->setIgnoreClassesWithNamespace($ignoreClasses)
            ->setClassLoader(ClassTool::findLoader());
        $context = ModeAContext::make()
            ->setParam($param);
        $this->shipshapeExecute(ModeAConfig::make(), $context);
    }

    private function modeB(string $target, array $ignoreClasses): void
    {
        $param = ModeBParam::make()
            ->setTarget($target)
            ->setIgnoreClassesWithNamespace($ignoreClasses)
            ->setClassLoader(ClassTool::findLoader());
        $context = ModeBContext::make()
            ->setParam($param)
            ->setService(new ModeBService());
        $this->shipshapeExecute(ModeBConfig::make(), $context);
    }

    private function modeC(string $target, array $ignoreClasses): void
    {
        $param = ModeCParam::make()
            ->setTarget($target)
            ->setIgnoreClassesWithNamespace($ignoreClasses)
            ->setClassLoader(ClassTool::findLoader());
        $context = ModeCContext::make()
            ->setParam($param)
            ->setService(new ModeCService());
        $this->shipshapeExecute(ModeCConfig::make(), $context);
    }
}
