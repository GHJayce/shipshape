<?php

declare(strict_types=1);

namespace Ghbjayce\MagicSocket\Common\Work\Entity\Context;

use Ghbjayce\MagicSocket\Common\Base\Attribute;

/**
 * @method Running _getRunning()
 * @method self _setRunning(Running $running)
 * @method mixed _getReturn()
 * @method self _setReturn(mixed $return)
 */
class Context extends Attribute
{
    protected Running $_running;
    protected mixed $_return = null;

    public function __construct()
    {
        $this->_running = new Running();
    }
}
