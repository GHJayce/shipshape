<?php

declare(strict_types=1);

namespace Ghjayce\Shipshape\Action;


use Ghjayce\Shipshape\Contract\ActionInterface;
use Ghjayce\Shipshape\Entity\Context\ClientContext;
use Ghjayce\Shipshape\Entity\Context\ExecuteContext;
use Ghjayce\Shipshape\Entity\Enum\ActionEnum;
use Ghjayce\Shipshape\Shipshape;

abstract class Action implements ActionInterface
{
    abstract public function process(ClientContext $context, ExecuteContext $executeContext);

    public function handle(ClientContext $context, ExecuteContext $executeContext): mixed
    {
        return $this->process($context, $executeContext);
    }

    public function execute(ClientContext $context, ExecuteContext $executeContext): ExecuteContext
    {
        $actionContext = $this->handle($context, $executeContext);
        $executeContext = $this->processResult($actionContext, $executeContext);
        $context = $executeContext->getClientContext();

        $result = $this->result($context, $executeContext);
        if ($result !== ActionEnum::RETURN_DATA_PLACEHOLDER) {
            $executeContext->setClientResult($result);
        }

        if ($this->return($context, $executeContext)) {
            $executeContext->exit();
        }
        return $executeContext;
    }

    public function result(ClientContext $context, ExecuteContext $executeContext): mixed
    {
        return ActionEnum::RETURN_DATA_PLACEHOLDER;
    }

    public function return(ClientContext $context, ExecuteContext $executeContext): bool
    {
        return false;
    }

    protected function processResult(mixed $result, ExecuteContext $executeContext): ExecuteContext
    {
        return Shipshape::processResult($result, $executeContext);
    }
}
