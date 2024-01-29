<?php
declare(strict_types=1);
namespace Ghbjayce\MagicSocket\Eutaxy\Action\Traits;

use Ghbjayce\MagicSocket\Common\Entity\Context\Context;
use Ghbjayce\MagicSocket\Common\Entity\Param\Param;
use Ghbjayce\MagicSocket\Eutaxy\Entity\Context\EutaxyContext;

trait TheEnd
{
    public function theEnd(
        Param $param,
        Context $context,
        EutaxyContext $eutaxyContext
    ): array|EutaxyContext
    {
        $eutaxyContext->markReturnSignal();
        return $eutaxyContext;
    }
}