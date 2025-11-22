<?php

declare(strict_types=1);

namespace Ghjayce\Shipshape\Entity\Context;

use Ghjayce\Shipshape\Entity\Config\Config;
use Ghjayce\Shipshape\Entity\Enum\SignalEnum;
use Phparm\Entity\Attribute;

/**
 * ========== property_hook_method ==========
 * @method Config getConfig()
 * @method string getActionName()
 * @method mixed|null getActionCallable()
 * @method mixed|null getClientResult()
 * @method ClientContext getClientContext()
 *
 * @method $this setConfig(Config $config)
 * @method $this setActionName(string $actionName)
 * @method $this setActionCallable(mixed|null $actionCallable)
 * @method $this setClientResult(mixed|null $clientResult)
 * @method $this setClientContext(ClientContext $clientContext)
 * ========== property_hook_method ==========
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
