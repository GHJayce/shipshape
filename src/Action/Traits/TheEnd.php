<?php
declare(strict_types=1);

namespace Ghjayce\Shipshape\Action\Traits;

use Ghjayce\Shipshape\Entity\Context\ClientContext;
use Ghjayce\Shipshape\Entity\Context\ExecuteContext;

trait TheEnd
{
    public function theEnd(ClientContext $context, ExecuteContext $executeContext): ExecuteContext
    {
        return $executeContext->exit();
    }
}
