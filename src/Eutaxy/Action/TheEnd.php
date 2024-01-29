<?php
declare(strict_types=1);
namespace Ghbjayce\MagicSocket\Common\Work\Action;

use Ghbjayce\MagicSocket\Common\Work\Action\Contract\ActionInterface;
use Ghbjayce\MagicSocket\Common\Work\Base\Action;
use Ghbjayce\MagicSocket\Common\Work\Entity\Context\Context;
use Ghbjayce\MagicSocket\Common\Work\Entity\Param\Param;
use Ghbjayce\MagicSocket\Common\Work\Tool\ResponseTool;

class TheEnd extends Action implements ActionInterface
{
    public function handle(
        Param $param,
        Context $context
    ): array
    {
        return $this->_response(
            context: ResponseTool::handleReturn($context),
        );
    }
}