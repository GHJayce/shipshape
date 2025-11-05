<?php

declare(strict_types=1);

namespace Ghjayce\Shipshape\Contract;

use Ghjayce\Shipshape\Entity\Context\ClientContext;
use Ghjayce\Shipshape\Entity\Context\ExecuteContext;

interface ActionInterface
{
    public function handle(ClientContext $context, ExecuteContext $executeContext): mixed;
    public function execute(ClientContext $context, ExecuteContext $executeContext): ExecuteContext;
    public function return(ClientContext $context, ExecuteContext $executeContext): bool;
    public function returnData(ClientContext $context, ExecuteContext $executeContext): mixed;
}