<?php

declare(strict_types=1);

namespace Ghjayce\Shipshape\Entity\Context;

use Ghjayce\Shipshape\Entity\Config\Config;
use Ghjayce\Shipshape\Entity\Enum\SignalEnum;
use Phparm\Entity\Attribute;

/**
 * @method $this setActionName(string $actionName)
 * @method string getActionName()
 * @method $this setActionCallable(callable $actionCallable)
 * @method callable|null getActionCallable()
 * @method $this setReturnData(mixed $returnData)
 * @method mixed getReturnData()
 * @method $this setClientContext(ClientContext $clientContext)
 * @method ClientContext getClientContext()
 * @method $this setConfig(Config $config)
 * @method Config getConfig()
 */
class ExecuteContext extends Attribute
{
    protected string $signal = '';
    public string $actionName = '';
    public $actionCallable = null;
    public mixed $returnData;
    public ClientContext $clientContext;
    public Config $config;

    public function isReturn(): bool
    {
        return $this->signal === SignalEnum::RETURN;
    }

    public function markReturn(): self
    {
        $this->signal = SignalEnum::RETURN;
        return $this;
    }
}
