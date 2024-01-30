<?php

declare(strict_types=1);

namespace Ghjayce\MagicSocket\Eutaxy;

use Ghjayce\MagicSocket\Eutaxy\Contract\EutaxyInterface;
use Ghjayce\MagicSocket\Eutaxy\Entity\Context\EutaxyContext;
use Ghjayce\MagicSocket\Eutaxy\Entity\Enum\ConfigEnum;
use Ghjayce\MagicSocket\Eutaxy\Tool\ConfigTool;
use Ghjayce\MagicSocket\Eutaxy\Tool\EutaxyTool;

class Eutaxy implements EutaxyInterface
{
    public function execute(
        array $config,
        EutaxyContext $context
    ): mixed
    {
        $mapping = $config[ConfigEnum::NAME_ACTION_MAPPING] ?? [];
        if (empty($mapping)) {
            throw new \Exception('Action mapping is empty.');
        }
        $beforeHook = ConfigTool::getHookBeforeCallable($config, [$this, 'beforeHook']);
        $processHook = ConfigTool::getHookProcessCallable($config, [$this, 'processHook']);
        $afterHook = ConfigTool::getHookAfterCallable($config, [$this, 'afterHook']);
        foreach ($mapping as $actionName => $actionCallableArray) {
            $context->setActionName($actionName)
                ->setActionCallableArray($actionCallableArray);

            $this->callFunction($beforeHook, $context);

            $result = $this->callFunction($processHook, $actionCallableArray, $context);
            $context = EutaxyTool::handleActionResult($result, $context);

            $this->callFunction($afterHook, $context);

            if ($context->isReturnSignal()) {
                return $context->getReturnData();
            }
        }
        return null;
    }

    protected function beforeHook(EutaxyContext $context): void
    {
    }

    protected function afterHook(EutaxyContext $context): void
    {
    }

    protected function processHook($actionCallable, EutaxyContext $context): array|EutaxyContext
    {
        return $this->callFunction(
            $actionCallable,
            param: $context->getClientParam(),
            context: $context->getClientContext(),
            eutaxyContext: $context
        );
    }

    protected function callFunction($callback, ...$params): mixed
    {
        return call_user_func($callback, ...$params);
    }
}
