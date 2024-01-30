<?php
declare(strict_types=1);
namespace Ghjayce\MagicSocket\Eutaxy\Action\Traits;

use Ghjayce\MagicSocket\Common\Entity\Context\Context;
use Ghjayce\MagicSocket\Common\Entity\Param\Param;
use Ghjayce\MagicSocket\Eutaxy\Entity\Context\EutaxyContext;

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