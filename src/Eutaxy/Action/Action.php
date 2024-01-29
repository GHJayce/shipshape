<?php

declare(strict_types=1);

namespace Ghbjayce\MagicSocket\Eutaxy\Action;


use Ghbjayce\MagicSocket\Common\Entity\Context\Context;
use Ghbjayce\MagicSocket\Common\Entity\Param\Param;
use Ghbjayce\MagicSocket\Eutaxy\Contract\ActionInterface;
use Ghbjayce\MagicSocket\Eutaxy\Entity\Context\EutaxyContext;
use Ghbjayce\MagicSocket\Eutaxy\Entity\Enum\ActionEnum;
use Ghbjayce\MagicSocket\Eutaxy\Tool\EutaxyTool;

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
