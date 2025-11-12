<?php

declare(strict_types=1);

namespace Example\Bathe\Action;

use Example\Bathe\Entity\BatheContext;
use Ghjayce\Shipshape\Action\Action;
use Ghjayce\Shipshape\Entity\Context\ClientContext;
use Ghjayce\Shipshape\Entity\Context\ExecuteContext;

class BrushTeeth extends Action
{
    /**
     * @param BatheContext $context
     * @param ExecuteContext $executeContext
     * @return void
     */
    public function process(ClientContext $context, ExecuteContext $executeContext): void
    {
        $context->status = 'brush teeth';
        $context->time->second += 180;
    }
}