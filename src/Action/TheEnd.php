<?php
declare(strict_types=1);

namespace Ghjayce\Shipshape\Action;

use Ghjayce\Shipshape\Entity\Context\ClientContext;
use Ghjayce\Shipshape\Entity\Context\ExecuteContext;

class TheEnd extends Action
{
    public function process(ClientContext $context, ExecuteContext $executeContext): void
    {
    }

    public function return(ClientContext $context, ExecuteContext $executeContext): bool
    {
        return true;
    }
}