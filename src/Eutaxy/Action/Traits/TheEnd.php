<?php
declare(strict_types=1);
namespace Ghbjayce\MagicSocket\Common\Work\Action\Traits;

use Ghbjayce\MagicSocket\Common\Work\Entity\Context\Context;
use Ghbjayce\MagicSocket\Common\Work\Entity\Param\Param;
use Ghbjayce\MagicSocket\Common\Work\Tool\ResponseTool;

trait TheEnd
{
    public function theEnd(
        Param $param,
        Context $context
    ): array
    {
        return ResponseTool::setResponse(
            context: ResponseTool::handleReturn($context),
        );
    }
}