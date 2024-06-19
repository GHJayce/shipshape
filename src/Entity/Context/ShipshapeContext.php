<?php

declare(strict_types=1);

namespace Ghjayce\Shipshape\Entity\Context;

use Ghjayce\Shipshape\Entity\Enum\Context\SignalEnum;

/**
 * @method string getSignal()
 * @method $this setSignal(string $signal)
 * @method $this setActionName(string $actionName)
 * @method string getActionName()
 * @method $this setActionCallable(callable $actionCallable)
 * @method callable|null getActionCallable()
 * @method $this setReturnData(mixed $returnData)
 * @method mixed getReturnData()
 * @method $this setClientContext(ClientContext $clientContext)
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
