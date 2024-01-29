<?php

declare(strict_types=1);

namespace Ghbjayce\MagicSocket\Common\Work\Tool;

use Ghbjayce\MagicSocket\Common\Work\Entity\Context\Context;
use Ghbjayce\MagicSocket\Common\Work\Entity\Enum\RunningInfo\SignalEnum;

class ResponseTool
{
    public static function set(
        array $actionContext,
        mixed $returnData = null
    ): array
    {
        return [
            'context' => $actionContext,
        ];
    }

    public static function setReturnData(
        Context $context,
        mixed $data
    ): Context
    {
        $context->_setReturn($data);
        return $context;
    }

    public static function isReturnSignal(Context $context): bool
    {
        return $context->_getRunning()->getSignal() === SignalEnum::RETURN;
    }

    public static function getReturnData(Context $context): mixed
    {
        return $context->_getReturn();
    }

    public static function handleActionResult(
        Context $context,
        array $result
    ): Context
    {
        $resultContext = $result['context'] ?? [];
        if (is_array($resultContext)) {
            $context->_fillAttributes($resultContext);
        }
        return $context;
    }
}
