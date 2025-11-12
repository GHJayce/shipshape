<?php

declare(strict_types=1);

namespace GhjayceTest\Shipshape\Example\FirstCast;

use Ghjayce\Shipshape\Entity\Context\ClientContext;
use Ghjayce\Shipshape\Entity\Context\ExecuteContext;

class Bathe
{
    public function undressing(ClientContext $context, ExecuteContext $executeContext): ExecuteContext
    {
        // do something...

        return $executeContext;
    }

    public function washing(ClientContext $context, ExecuteContext $executeContext): ExecuteContext
    {
        // do something...

        return $executeContext;
    }

    public function wipeUp(ClientContext $context, ExecuteContext $executeContext): ExecuteContext
    {
        // do something...

        return $executeContext;
    }

    public function getDressed(ClientContext $context, ExecuteContext $executeContext): ExecuteContext
    {
        // do something...

        return $executeContext;
    }
}