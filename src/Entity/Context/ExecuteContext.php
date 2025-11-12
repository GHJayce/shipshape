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
 * @method $this setClientResult(mixed $clientResult)
 * @method mixed getClientResult()
 * @method $this setClientContext(ClientContext $clientContext)
 * @method ClientContext getClientContext()
 * @method $this setConfig(Config $config)
 * @method Config getConfig()
 */
class ExecuteContext extends Attribute
{
    protected int $signal = 0;

    public Config $config;
    public string $actionName = '';
    public mixed $actionCallable = null;
    public mixed $clientResult = null;
    public ClientContext $clientContext;

    public function isExit(): bool
    {
        return $this->signal === SignalEnum::EXIT;
    }

    public function exit(): self
    {
        $this->signal = SignalEnum::EXIT;
        return $this;
    }
}
