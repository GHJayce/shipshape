<?php

declare(strict_types=1);

namespace Example\Bathe\Action;

use Example\Bathe\Entity\BatheContext;
use Ghjayce\Shipshape\Action\Action;
use Ghjayce\Shipshape\Entity\Context\ClientContext;
use Ghjayce\Shipshape\Entity\Context\ExecuteContext;

class WipeUp extends Action
{

    /**
     * @param BatheContext $context
     * @param ExecuteContext $executeContext
     */
    public function process(ClientContext $context, ExecuteContext $executeContext): void
    {
        $context->setStatus('wipeup');
        $time = $context->getTime();
        $time->setSecond($time->getSecond() + 30);
    }
}