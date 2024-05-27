<?php

declare(strict_types=1);

namespace Ghjayce\Shipshape\Entity\Context;

use Ghjayce\Shipshape\Entity\Enum\Context\SignalEnum;

/**
 * @method string getSignal()
 * @method self setSignal(string $signal)
 * @method self setActionName(string $actionName)
 * @method string getActionName()
 * @method self setActionCallable(callable $actionCallable)
 * @method callable|null getActionCallable()
 * @method self setReturnData(mixed $returnData)
 * @method mixed getReturnData()
 * @method self setClientContext(ClientContext $clientContext)
 * @method ClientContext getClientContext()
 */
class ShipshapeContext extends ClientContext
{
    protected string $signal = '';
    protected string $actionName = '';
    protected $actionCallable = null;
    protected mixed $returnData;
    protected ClientContext $clientContext;

    public function isReturnSignal(): bool
    {
        return $this->signal === SignalEnum::RETURN;
    }

    public function markReturnSignal(): self
    {
        $this->signal = SignalEnum::RETURN;
        return $this;
    }
}
