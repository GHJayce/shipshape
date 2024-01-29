<?php

declare(strict_types=1);

namespace Ghbjayce\MagicSocket\Common\Work\Tool;

use Ghbjayce\MagicSocket\Common\Work\Entity\Context\Context;
use Ghbjayce\MagicSocket\Common\Work\Entity\Context\Running;
use Ghbjayce\MagicSocket\Common\Work\Entity\Enum\RunningInfo\SignalEnum;

class RunningTool
{
    public static function setSignal(Running $running, string $signal): Running
    {
        $running->setSignal($signal);
        return $running;
    }

    public static function setReturnSignal(
        Context $context
    ): Context
    {
        $context->_setRunning(
            self::setSignal($context->_getRunning(), SignalEnum::RETURN)
        );
        return $context;
    }
}
