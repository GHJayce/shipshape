<?php

declare(strict_types=1);

namespace Ghbjayce\MagicSocket\Eutaxy;

use Ghbjayce\MagicSocket\Common\Work\Action\Contract\EutaxyInterface;
use Ghbjayce\MagicSocket\Common\Work\Entity\Context\Context;
use Ghbjayce\MagicSocket\Common\Work\Entity\Enum\RunningInfo\SignalEnum;
use Ghbjayce\MagicSocket\Common\Work\Entity\Param\Param;
use Ghbjayce\MagicSocket\Common\Work\Tool\ResponseTool;
use Ghbjayce\MagicSocket\Eutaxy\Work\Entity\Enum\ConfigEnum;
use Ghbjayce\MagicSocket\Eutaxy\Work\Tool\Config\ConfigTool;

class Eutaxy implements EutaxyInterface
{
    public function execute(
        array $config,
        Param $param,
        Context $context
    ): mixed
    {
        $mapping = $config[ConfigEnum::NAME_ACTION_MAPPING] ?? [];
        if (empty($mapping)) {
            throw new \Exception('Action mapping is empty.');
        }
        $beforeHook = ConfigTool::getHookBeforeCallable($config, [$this, 'beforeHook']);
        $processHook = ConfigTool::getHookProcessCallable($config, [$this, 'processHook']);
        $afterHook = ConfigTool::getHookAfterCallable($config, [$this, 'afterHook']);
        foreach ($mapping as $actionName => $actionCallable) {
            $context->_getRunning()
                ->setActionName($actionName)
                ->setActionCallable($actionCallable);

            $this->callFunction($beforeHook, $param, $context);

            $result = $this->callFunction($processHook, $actionCallable, $param, $context);
            $context = ResponseTool::handleActionResult($context, $result);

            $this->callFunction($afterHook, $param, $context);

            if (ResponseTool::isReturnSignal($context)) {
                return ResponseTool::getReturnData($context);
            }
        }
        return null;
    }

    protected function beforeHook(
        Param $param,
        Context $context
    ): void
    {
    }

    protected function afterHook(
        Param $param,
        Context $context
    ): void
    {
    }

    protected function processHook(
        array $actionCallable,
        Param $param,
        Context $context
    ): array
    {
        return $this->callFunction($actionCallable, $param, $context);
    }

    protected function callFunction(callable $callback, ...$params): mixed
    {
        return call_user_func($callback, ...$params);
    }
}
