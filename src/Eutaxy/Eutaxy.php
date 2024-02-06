<?php

declare(strict_types=1);

namespace Ghjayce\MagicSocket\Eutaxy;

use Ghjayce\MagicSocket\Eutaxy\Contract\EutaxyInterface;
use Ghjayce\MagicSocket\Eutaxy\Entity\Config\EutaxyConfig;
use Ghjayce\MagicSocket\Eutaxy\Entity\Context\EutaxyContext;
use Ghjayce\MagicSocket\Eutaxy\Support\Tool\EutaxyTool;

class Eutaxy implements EutaxyInterface
{
    public function execute(
        EutaxyConfig $config,
        EutaxyContext $context
    ): mixed
    {
        $mapping = $config->getMapping();
        if (!$mapping) {
            throw new \Exception('Action mapping is empty.');
        }
        foreach ($mapping as $actionName => $actionCallable) {
            $context->setActionName($actionName)
                ->setActionCallable($actionCallable);

            $this->beforeHandle($config, $context);

            $context = $this->processHandle($actionCallable, $config, $context);

            $this->afterHandle($config, $context);

            if ($context->isReturnSignal()) {
                return $context->getReturnData();
            }
        }
        return null;
    }

    protected function beforeHandle(EutaxyConfig $config, EutaxyContext $context): void
    {
        if ($callable = $config->getBeforeExecuteHook()) {
            $this->call($callable, config: $config, context: $context);
        }
    }

    protected function processHandle($actionCallable, EutaxyConfig $config, EutaxyContext $context): EutaxyContext
    {
        if ($callable = $config->getProcessExecuteHook()) {
            $result = $this->call($callable, actionCallable: $actionCallable, config: $config, context: $context);
        } else {
            $result = $this->call(
                $actionCallable,
                param: $context->getClientParam(),
                context: $context->getClientContext(),
                eutaxyContext: $context
            );
        }
        return EutaxyTool::handleActionResult($result, $context);
    }

    protected function afterHandle(EutaxyConfig $config, EutaxyContext $context): void
    {
        if ($callable = $config->getAfterExecuteHook()) {
            $this->call($callable, config: $config, context: $context);
        }
    }

    protected function call($callback, ...$params): mixed
    {
        if (is_callable($callback)) {
            return call_user_func($callback, ...$params);
        }
        return null;
    }
}
