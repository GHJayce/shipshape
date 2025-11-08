<?php

declare(strict_types=1);

namespace Example;

use Ghjayce\Shipshape\Action\Action;
use Ghjayce\Shipshape\Entity\Context\ClientContext;
use Ghjayce\Shipshape\Entity\Context\ExecuteContext;

class FirstAction extends Action
{

    public function handle(ClientContext $context, ExecuteContext $executeContext): mixed
    {
        return null;
    }
}