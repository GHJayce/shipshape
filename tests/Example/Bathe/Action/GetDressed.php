<?php

declare(strict_types=1);

namespace Example\Bathe\Action;

use Example\Bathe\Entity\BatheContext;
use Ghjayce\Shipshape\Action\Action;
use Ghjayce\Shipshape\Entity\Context\ClientContext;
use Ghjayce\Shipshape\Entity\Context\ExecuteContext;

class GetDressed extends Action
{

    /**
     * @param BatheContext $context
     * @param ExecuteContext $executeContext
     * @return mixed
     */
    public function handle(ClientContext $context, ExecuteContext $executeContext): mixed
    {
        $context->setStatus('getDressed');
        $time = $context->getTime();
        $time->setSecond($time->getSecond() + 20);
    }
}