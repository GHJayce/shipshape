<?php

declare(strict_types=1);

namespace GhjayceTest\Shipshape\Example\Bathe\Action;

use GhjayceTest\Shipshape\Example\Bathe\Entity\WashupContext;
use Ghjayce\Shipshape\Action\Action;
use Ghjayce\Shipshape\Entity\Context\ClientContext;
use Ghjayce\Shipshape\Entity\Context\ExecuteContext;

class Washup extends Action
{
    /**
     * @param WashupContext $context
     * @param ExecuteContext $executeContext
     * @return void
     */
    public function process(ClientContext $context, ExecuteContext $executeContext): void
    {
        $context->batheContext->status = 'wash up';
        $context->batheContext->time->second += 300;
    }
}