<?php

declare(strict_types=1);

namespace Ghbjayce\MagicSocket\Eutaxy\List;

use Ghbjayce\MagicSocket\Common\Work\Base\Work;
use Ghbjayce\MagicSocket\Common\Work\Entity\Param\Param;
use Hyperf\Di\Annotation\Inject;
use Ghbjayce\MagicSocket\Eutaxy\List\Entity\Enum\RosterEnum;
use Ghbjayce\MagicSocket\Eutaxy\Eutaxy;
use Ghbjayce\MagicSocket\Eutaxy\Work\Tool\Config\ConfigTool;

class WorkService extends Work
{
    #[Inject]
    protected Eutaxy $actionService;

    public function addBeforeHook(
        Param $param,
        $context
    ): array
    {
        $roster = [];
        foreach (RosterEnum::ACTION as $name) {
            $roster[] = 'before'.ucfirst($name);
            $roster[] = $name;
        }
        return $this->_response(
            context: compact(
                'roster',
            )
        );
    }

    public function actionConfig(
        Param $param,
        $context
    ): array
    {
        $actionConfig = ConfigTool::toConfig($context->getRoster(), $context->getBusinessConfig());
        return $this->_response(
            context: compact(
                'actionConfig',
            )
        );
    }

    public function execute(
        Param $param,
        $context
    ): array
    {
        $returnData = $this->actionService->execute($context->getActionConfig(), $param, $context->getBusinessContext());
        return $this->_response(
            context: compact('returnData')
        );
    }
}
