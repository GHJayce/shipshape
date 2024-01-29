<?php

declare(strict_types=1);

namespace Ghbjayce\MagicSocket\Eutaxy\Entity\Context;


use Ghbjayce\MagicSocket\Common\Entity\Context\Context;
use Ghbjayce\MagicSocket\Common\Entity\Param\Param;
use Ghbjayce\MagicSocket\Eutaxy\Entity\Enum\Context\SignalEnum;

/**
 * @method string getSignal()
 * @method self setSignal(string $signal)
 * @method self setActionName(string $actionName)
 * @method string getActionName()
 * @method self setActionCallableArray(array $actionCallableArray)
 * @method array getActionCallableArray()
 * @method self setReturnData(mixed $returnData)
 * @method mixed getReturnData()
 * @method self setClientContext(Context $clientContext)
 * @method Context getClientContext()
 * @method self setClientParam(Param $clientParam)
 * @method Param getClientParam()
 */
class EutaxyContext extends Context
{
    protected string $signal = '';
    protected string $actionName = '';
    protected array $actionCallableArray = [];
    protected mixed $returnData;
    protected Context $clientContext;
    protected Param $clientParam;

    public function isReturnSignal(): bool
    {
        return $this->signal === SignalEnum::RETURN;
    }

    public function markReturnSignal(): self
    {
        $this->signal = SignalEnum::RETURN;
        return $this;
    }

    public function fillClientContext(array $context): self
    {
        $this->clientContext->_fillAttributes($context);
        return $this;
    }
}
