<?php

declare(strict_types=1);

namespace GhjayceTest\Shipshape\Example\FirstCast\Action;

use Ghjayce\Shipshape\Action\Action;
use Ghjayce\Shipshape\Entity\Context\ClientContext;
use Ghjayce\Shipshape\Entity\Context\ExecuteContext;

class SecondAction extends Action
{

    public function process(ClientContext $context, ExecuteContext $executeContext): void
    {
    }
}