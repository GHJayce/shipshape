<?php

declare(strict_types=1);

namespace Ghjayce\Shipshape\Action;


use Ghjayce\Shipshape\Contract\ActionInterface;
use Ghjayce\Shipshape\Entity\Context\ClientContext;
use Ghjayce\Shipshape\Entity\Context\ShipshapeContext;
use Ghjayce\Shipshape\Entity\Enum\ActionEnum;
use Ghjayce\Shipshape\Shipshape;

abstract class Action implements ActionInterface
{
    public function execute(ClientContext $context, ShipshapeContext $shipshapeContext): ShipshapeContext
    {
        $actionContext = $this->handle($context, $shipshapeContext);
        $shipshapeContext = Shipshape::handleActionResult($actionContext, $shipshapeContext);
        $context = $shipshapeContext->getClientContext();

        $returnData = $this->returnData($context, $shipshapeContext);
        if ($returnData !== ActionEnum::RETURN_DATA_PLACEHOLDER) {
            $shipshapeContext->setReturnData($returnData);
        }

        if ($this->return($context, $shipshapeContext)) {
            $shipshapeContext->markReturnSignal();
        }
        return $shipshapeContext;
    }

    public function returnData(ClientContext $context, ShipshapeContext $shipshapeContext): mixed
    {
        return ActionEnum::RETURN_DATA_PLACEHOLDER;
    }

    public function return(ClientContext $context, ShipshapeContext $shipshapeContext): bool
    {
        return false;
    }
}
