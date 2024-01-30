<?php

declare(strict_types=1);

namespace Ghjayce\MagicSocket\Eutaxy\Action;


use Ghjayce\MagicSocket\Common\Entity\Context\Context;
use Ghjayce\MagicSocket\Common\Entity\Param\Param;
use Ghjayce\MagicSocket\Eutaxy\Contract\ActionInterface;
use Ghjayce\MagicSocket\Eutaxy\Entity\Context\EutaxyContext;
use Ghjayce\MagicSocket\Eutaxy\Entity\Enum\ActionEnum;
use Ghjayce\MagicSocket\Eutaxy\Tool\EutaxyTool;

abstract class Action implements ActionInterface
{
    public function execute(
        Param $param,
        Context $context,
        EutaxyContext $eutaxyContext
    ): array|EutaxyContext
    {
        $actionContext = $this->handle($param, $context, $eutaxyContext);
        $eutaxyContext = EutaxyTool::handleActionResult($actionContext, $eutaxyContext);

        $returnData = $this->returnData($param, $context, $eutaxyContext);
        if ($returnData !== ActionEnum::RETURN_DATA_PLACEHOLDER) {
            $eutaxyContext->setReturnData($returnData);
        }

        if ($this->return($param, $context, $eutaxyContext)) {
            $eutaxyContext->markReturnSignal();
        }
        return $eutaxyContext;
    }

    public function returnData(Param $param, Context $context, EutaxyContext $eutaxyContext): mixed
    {
        return ActionEnum::RETURN_DATA_PLACEHOLDER;
    }

    public function return(Param $param, Context $context, EutaxyContext $eutaxyContext): bool
    {
        return false;
    }
}
