<?php

declare(strict_types=1);

namespace GhjayceTest\Shipshape\Example\Bathe\Action;

use GhjayceTest\Shipshape\Example\Bathe\Entity\BatheContext;
use GhjayceTest\Shipshape\Example\Bathe\Entity\WashupContext;
use Ghjayce\Shipshape\Action\Action;
use Ghjayce\Shipshape\Entity\Config\ActionConfig;
use Ghjayce\Shipshape\Entity\Context\ClientContext;
use Ghjayce\Shipshape\Entity\Context\ExecuteContext;
use Ghjayce\Shipshape\Shipshape;

class Washing extends Action
{

    /**
     * @param BatheContext $context
     * @param ExecuteContext $executeContext
     */
    public function process(ClientContext $context, ExecuteContext $executeContext): void
    {
        $shipshape = new Shipshape();
        $subLevelExecuteContext = ExecuteContext::make()
            ->setClientContext($context)
            ->setConfig(
                ActionConfig::make()->setActions([
                    BrushTeeth::class,
                ])
            );
        $shipshape->execute($subLevelExecuteContext);
        if ($context->status === 'brush teeth') {
            $context->time->second += 2;
        }


        $secondLevelExecuteContext = ExecuteContext::make()
            ->setClientContext(WashupContext::make()->setBatheContext($context))
            ->setConfig(
                ActionConfig::make()->setActions([
                    Washup::class,
                ])
            );
        $shipshape->execute($secondLevelExecuteContext);
        if ($context->status === 'wash up') {
            $context->time->second += 3;
        }


        $context->status = 'washing';
        $context->time->second += 720;
    }
}