<?php

declare(strict_types=1);

namespace Ghjayce\Shipshape;

use Ghjayce\Shipshape\Contract\ShipshapeInterface;
use Ghjayce\Shipshape\Entity\Config\ShipshapeConfig;
use Ghjayce\Shipshape\Entity\Context\ClientContext;
use Ghjayce\Shipshape\Entity\Context\ShipshapeContext;

class Shipshape implements ShipshapeInterface
{
    public const VERSION = '0.3.1';

    public function execute(ShipshapeConfig $config, ShipshapeContext $context): mixed
    {
        $works = $config->getWorks();
        foreach ($works as $actionName => $actionCallable) {
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

    protected function beforeHandle(ShipshapeConfig $config, ShipshapeContext $context): void
    {
        if ($callable = $config->getBeforeHandleHook()) {
            $this->call($callable, config: $config, context: $context);
        }
    }

    protected function processHandle($actionCallable, ShipshapeConfig $config, ShipshapeContext $context): ShipshapeContext
    {
        if ($callable = $config->getProcessHandleHook()) {
            $result = $this->call($callable, actionCallable: $actionCallable, config: $config, context: $context);
        } else {
            $result = $this->call(
                $actionCallable,
                context: $context->getClientContext(),
                shipshapeContext: $context
            );
        }
        return self::handleActionResult($result, $context);
    }

    protected function afterHandle(ShipshapeConfig $config, ShipshapeContext $context): void
    {
        if ($callable = $config->getAfterHandleHook()) {
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

    public static function handleActionResult(mixed $result, ShipshapeContext $context): ShipshapeContext
    {
        if (is_array($result)) {
            $context->setClientContext(
                $context->getClientContext()->fillProperty($result)
            );
        } elseif ($result instanceof ShipshapeContext) {
            $context = $result;
        } elseif ($result instanceof ClientContext) {
            $context->setClientContext($result);
        }
        return $context;
    }
}
