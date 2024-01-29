<?php

declare(strict_types=1);

namespace Ghbjayce\MagicSocket\Common\Work\Entity\Context;

use Ghbjayce\MagicSocket\Common\Base\Attribute;

/**
 * @method string getSignal()
 * @method self setSignal(string $signal)
 * @method self setActionName(string $actionName)
 * @method string getActionName()
 * @method self setActionCallable(array $actionCallable)
 * @method array getActionCallable()
 */
class Running extends Attribute
{
    protected string $signal = '';
    protected string $actionName = '';
    protected array $actionCallable = [];
}
