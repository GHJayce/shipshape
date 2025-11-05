<?php

declare(strict_types=1);

namespace Ghjayce\Shipshape\Action;


use Ghjayce\Shipshape\Contract\ActionInterface;
use Ghjayce\Shipshape\Entity\Context\ClientContext;
use Ghjayce\Shipshape\Entity\Context\ExecuteContext;
use Ghjayce\Shipshape\Entity\Enum\ActionEnum;
use Ghjayce\Shipshape\Shipshape;

abstract class Action implements ActionInterface
{
    public function execute(ClientContext $context, ExecuteContext $executeContext): ExecuteContext
    {
        $actionContext = $this->handle($context, $executeContext);
        $executeContext = $this->processResult($actionContext, $executeContext);
        $context = $executeContext->getClientContext();

        $returnData = $this->returnData($context, $executeContext);
        if ($returnData !== ActionEnum::RETURN_DATA_PLACEHOLDER) {
            $executeContext->setReturnData($returnData);
        }

        if ($this->return($context, $executeContext)) {
            $executeContext->markReturn();
        }
        return $executeContext;
    }

    public function returnData(ClientContext $context, ExecuteContext $executeContext): mixed
    {
        return ActionEnum::RETURN_DATA_PLACEHOLDER;
    }

    public function return(ClientContext $context, ExecuteContext $executeContext): bool
    {
        return false;
    }

    protected function processResult(mixed $result, ExecuteContext $executeContext): ExecuteContext
    {
        return Shipshape::processResult($result, $executeContext);
    }
}
