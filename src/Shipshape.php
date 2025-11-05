<?php

declare(strict_types=1);

namespace Ghjayce\Shipshape;

use Ghjayce\Shipshape\Contract\ExecuteInterface;
use Ghjayce\Shipshape\Entity\Context\ClientContext;
use Ghjayce\Shipshape\Entity\Context\ExecuteContext;

class Shipshape implements ExecuteInterface
{
    public const VERSION = '1.0.0';

    public function execute(ExecuteContext $executeContext): mixed
    {
        $config = $executeContext->getConfig();
        if (!$config->isBuilt()) {
            $config->build();
        }
        $works = $config->getWorks();
        foreach ($works as $actionName => $actionCallable) {
            $executeContext->setActionName($actionName)
                ->setActionCallable($actionCallable);

            $this->before($executeContext);

            $executeContext = $this->process($actionCallable, $executeContext);

            $this->after($executeContext);

            if ($executeContext->isReturn()) {
                return $executeContext->getReturnData();
            }
        }
        return null;
    }

    protected function before(ExecuteContext $executeContext): void
    {
        $config = $executeContext->getConfig();
        if ($callable = $config->getHook()?->getBefore()) {
            $this->call($callable, executeContext: $executeContext);
        }
    }

    protected function process($actionCallable, ExecuteContext $executeContext): ExecuteContext
    {
        $config = $executeContext->getConfig();
        if ($callable = $config->getHook()?->getAfter()) {
            $result = $this->call($callable, actionCallable: $actionCallable, context: $executeContext);
        } else {
            $result = $this->call(
                $actionCallable,
                context: $executeContext->getClientContext(),
                executeContext: $executeContext
            );
        }
        return self::processResult($result, $executeContext);
    }

    protected function after(ExecuteContext $executeContext): void
    {
        $config = $executeContext->getConfig();
        if ($callable = $config->getHook()?->getAfter()) {
            $this->call($callable, executeContext: $executeContext);
        }
    }

    protected function call($callback, ...$params): mixed
    {
        if (is_callable($callback)) {
            return call_user_func($callback, ...$params);
        }
        return null;
    }

    public static function processResult(mixed $result, ExecuteContext $executeContext): ExecuteContext
    {
        if (is_array($result)) {
            $executeContext->setClientContext($executeContext->getClientContext()->fill($result));
        } elseif ($result instanceof ExecuteContext) {
            $executeContext = $result;
        } elseif ($result instanceof ClientContext) {
            $executeContext->setClientContext($result);
        }
        return $executeContext;
    }
}
