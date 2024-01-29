<?php

declare(strict_types=1);

namespace Ghbjayce\MagicSocket\Common\Work\Base;

use Ghbjayce\MagicSocket\Common\Work\Action\Contract\ActionInterface;
use Ghbjayce\MagicSocket\Common\Work\Base\Traits\Response;
use Ghbjayce\MagicSocket\Common\Work\Entity\Context\Context;
use Ghbjayce\MagicSocket\Common\Work\Entity\Param\Param;

abstract class Action implements ActionInterface
{
    use Response;

    public function execute(Param $param, Context $context): array
    {
        $actionContext = $this->handle($param, $context);
        $this->returnData();
        if ($this->return($param, $context)) {
            $context = $this->_setReturnSignal($context);
        }
        return $this->_response(
            $context,
        );
    }
}
