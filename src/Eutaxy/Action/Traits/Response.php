<?php

declare(strict_types=1);

namespace Ghbjayce\MagicSocket\Common\Work\Base\Traits;

use Ghbjayce\MagicSocket\Common\Work\Entity\Context\Context;
use Ghbjayce\MagicSocket\Common\Work\Tool\ResponseTool;
use Ghbjayce\MagicSocket\Common\Work\Tool\RunningTool;

trait Response
{
    protected function _response(
        array $actionContext,
        mixed $returnData = null
    ): array
    {
        return ResponseTool::set(
            actionContext: $actionContext,
        );
    }

    protected function _setReturnSignal(Context $context): Context
    {
        return RunningTool::setReturnSignal($context);
    }


}
