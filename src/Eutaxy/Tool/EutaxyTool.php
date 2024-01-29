<?php

declare(strict_types=1);

namespace Ghbjayce\MagicSocket\Eutaxy\Tool;

use Ghbjayce\MagicSocket\Eutaxy\Entity\Context\EutaxyContext;

class EutaxyTool
{
    public static function handleActionResult(
        array|EutaxyContext $result,
        EutaxyContext $context
    ): EutaxyContext
    {
        if (is_array($result)) {
            $context->setClientContext(
                $context->getClientContext()->_fillAttributes($result)
            );
        } elseif ($result instanceof EutaxyContext) {
            $context = $result;
        }
        return $context;
    }
}
